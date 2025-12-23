<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form;

use function YOOtheme\app;
use YOOtheme\Arr;
use YOOtheme\File;
use YOOtheme\Path;
use YOOtheme\Str;
use YOOtheme\Url;
use YOOtheme\Http\Message\UploadedFile;
use ZOOlanders\YOOessentials\Util;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionRequest;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;
use ZOOlanders\YOOessentials\Form\Validation\Size;
use ZOOlanders\YOOessentials\Vendor\Respect\Validation\Exceptions\ContainsAnyException;
use ZOOlanders\YOOessentials\Vendor\Respect\Validation\Exceptions\RegexException;
use ZOOlanders\YOOessentials\Vendor\Respect\Validation\Exceptions\ValidationException;
use ZOOlanders\YOOessentials\Vendor\Respect\Validation\Factory;
use ZOOlanders\YOOessentials\Vendor\Respect\Validation\Rules\Extension;
use ZOOlanders\YOOessentials\Vendor\Respect\Validation\Rules\Mimetype;
use ZOOlanders\YOOessentials\Vendor\Respect\Validation\Validator;

return [
    'transforms' => [
        'render' => function (object $node, array $params) {
            /** @var FormSubmissionRequest $submission */
            $submission = app(FormSubmissionRequest::class);

            $controlName = $node->controls->field['name'];
            $controlProps = $node->controls->field['props'];

            $fieldset = Util\Node::findClosestType($params, 'yooessentials_form_fieldset');

            if ($fieldset && !$fieldset->props['fields_show_label']) {
                $controlProps['label'] = '';
            }

            $node->control = (object) [
                'name' => $controlName,
                'id' => $controlProps['id'] ?? null,
                'errors' => $submission->validator()->errors($controlName) ?? [],
                'value' => $submission->data($controlName),
                'props' => $controlProps,
            ];

            $node->button = Util\Prop::filterByPrefix($node->props, 'button_');
        },
    ],

    'controls' => [
        'field' => function ($node) {
            $props = Util\Prop::filterByPrefix($node->props, 'control_');

            return ['props' => $props];
        },
    ],

    'validation' => function ($control, Validator $validator, FormSubmissionRequest $submission) {
        $props = $control['props'];
        $files = $submission->request()->getUploadedFile($control['name']);

        $mimeTypes = $props['mimetypes'] ?? '';
        $mimeTypes = array_filter(explode(',', str_replace(' ', '', $mimeTypes)));

        $extensions = $props['extensions'] ?? '';
        $extensions = array_filter(explode(',', str_replace([' ', '.'], '', $extensions)));

        /** @var UploadedFile $file */
        foreach (Arr::wrap($files) as $file) {
            if ($file->getError()) {
                continue;
            }

            try {
                if (count($mimeTypes) > 0) {
                    try {
                        $v = clone $validator;

                        $validations = array_map(fn ($type) => Validator::regex('#' . str_replace('*', '[a-z]*', $type) . '#'), $mimeTypes);

                        $fileMimeType = $file->getClientMediaType();

                        // Forcibly read it from disk to get the REAL mimetype, not the one
                        // from the extension of the file, see
                        // https://github.com/joolanders/yooessentials/issues/414
                        $tmpFilePath = $file->getStream()->getMetadata('uri');
                        if ($tmpFilePath) {
                            $finfo = new \finfo();
                            $storedFileMimeType = $finfo->file($tmpFilePath, \FILEINFO_MIME_TYPE);
                            if ($storedFileMimeType) {
                                $fileMimeType = $storedFileMimeType;
                            }
                        }

                        $v->anyOf(...$validations)->check($fileMimeType);
                    } catch (RegexException $e) {
                        throw Factory::getDefaultInstance()->exception(new Mimetype(implode(',', $mimeTypes)), $e->getId());
                    }
                }

                if (count($extensions) > 0) {
                    try {
                        $v = clone $validator;
                        $v->containsAny($extensions)->check($file->getClientFilename());
                    } catch (ContainsAnyException $e) {
                        throw Factory::getDefaultInstance()->exception(
                            new Extension(implode(',', $extensions)),
                            $e->getId()
                        );
                    }
                }

                $minSize = str_replace(' ', '', trim($props['min_filesize'] ?? ''));
                $maxSize = str_replace(' ', '', trim($props['max_filesize'] ?? ''));

                if ($minSize || $maxSize) {
                    try {
                        (new Size($minSize, $maxSize))->check($file);
                    } catch (ValidationException $e) {
                        throw Factory::getDefaultInstance()->exception(new Size($minSize, $maxSize), $validator->getName());
                    }
                }
            } catch (ValidationException $e) {
                throw $e;
            }
        }

        $required = $props['required'] ?? false;

        if ($required) {
            $v = clone $validator;
            $v->notOptional();

            foreach (Arr::wrap($files) as $file) {
                $v->notOptional()->check($file->getClientFilename());
            }
        }

        return $validator;
    },

    'submission' => function ($control, FormSubmissionRequest $submission, FormSubmissionResponse $response) {
        $props = $control['props'];
        $path = trim($props['upload_path'] ?? '');
        $controlName = $control['name'];
        $uniqueFilenames = $props['unique_filenames'] ?? true;
        $overrideFilename = trim($props['upload_filename'] ?? '');
        $errorTmpl = 'File Submission Error: %s';
        $childDir = app()->config->get('theme.childDir');

        try {
            if (!$path || Url::isValid($path)) {
                throw new \RuntimeException('Destination path is missing or is invalid.');
            }

            if (!Str::startsWith($path, '~') && !Str::startsWith($path, '/')) {
                $path = "~/$path";
            }

            // when using child theme the base path base must be resolved independently
            if (Str::startsWith($path, '~theme') && $childDir) {
                $path = Path::join($childDir, str_replace('~theme', '', $path));
            } else {
                $path = Path::resolve($path);
            }

            // resolve path tags
            $path = $submission->parseTags($path);

            if (@File::makeDir($path, 0777, true) === false) {
                throw new \RuntimeException('Destination path is not reachable.');
            }
        } catch (\Throwable $e) {
            return $response->withErrors([
                sprintf("Configuration error for field '%s': %s", $controlName, $e->getMessage()),
            ]);
        }

        // Fix php converting spaces and dots into underscores
        // @see https://www.php.net/manual/en/language.variables.external.php
        $phpControlName = str_replace('.', '_', str_replace(' ', '_', $controlName));
        $files = Arr::wrap($submission->request()->getUploadedFile($phpControlName));

        // Try to use the normal control name, php is weird
        if (count($files) <= 0 && $phpControlName !== $controlName) {
            $files = Arr::wrap($submission->request()->getUploadedFile($controlName));
        }

        /** @var UploadedFile $file */
        foreach ($files as $i => $file) {
            if (!$file instanceof UploadedFile || $file->getError()) {
                continue;
            }

            $filename = $file->getClientFilename();

            if (!empty($overrideFilename)) {
                $extension = File::getExtension($filename);
                $filename = "$overrideFilename.$extension";
            }

            $destination = "$path/$filename";

            if ($uniqueFilenames) {
                $destination = Util\File::getUniqueFilepath($destination);
            }

            try {
                $file->moveTo(Path::get($destination));

                // Override submission data
                $data = $submission->data();
                $relative = Path::relative('~', $destination);

                if ($props['multiple'] ?? false) {
                    $data[$controlName][$i] = $relative;
                } else {
                    $data[$controlName] = $relative;
                }

                $submission->setData($data);
            } catch (\Throwable $e) {
                $response->withErrors([sprintf($errorTmpl, $e->getMessage())]);

                break;
            }
        }
    },
];
