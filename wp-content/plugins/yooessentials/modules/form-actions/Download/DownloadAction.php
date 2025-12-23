<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Download;

use YOOtheme\File;
use YOOtheme\Str;
use YOOtheme\Url;
use ZOOlanders\YOOessentials\Encryption\Encrypter;
use ZOOlanders\YOOessentials\Form\Action\ActionConfigurationException;
use ZOOlanders\YOOessentials\Form\Action\StandardAction;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;
use function YOOtheme\app;

class DownloadAction extends StandardAction
{
    public const NAME = 'download';

    public function __invoke(FormSubmissionResponse $response, callable $next): FormSubmissionResponse
    {
        $file = $this->config('file', '');
        $file = $response->submission()->parseTags($file);

        if ($file && !Str::startsWith($file, '~') && !Str::startsWith($file, '/')) {
            $file = File::get("~/$file");
        }

        if (!$file) {
            throw ActionConfigurationException::create($this, 'File Not Found.');
        }

        /** @var Encrypter $encrypter */
        $encrypter = app(Encrypter::class);

        $file = $encrypter->encrypt($file);

        return $next(
            $response->withData([
                'download' => array_merge($response->getData()->get('download', []), [
                    Url::route(DownloadActionController::DOWNLOAD_URL, ['file' => $file]),
                ])
            ])
        );
    }
}
