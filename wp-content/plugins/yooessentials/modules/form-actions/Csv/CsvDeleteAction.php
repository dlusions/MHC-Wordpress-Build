<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Csv;

use YOOtheme\Path;
use YOOtheme\File;
use ZOOlanders\YOOessentials\Form\Action\StandardAction;
use ZOOlanders\YOOessentials\Form\Action\ActionConfigurationException;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;

class CsvDeleteAction extends StandardAction
{
    use InteractsWithCsv;

    public const NAME = 'csv-record-delete';

    public function __invoke(FormSubmissionResponse $response, callable $next): FormSubmissionResponse
    {
        $config = (object) $this->getConfig();

        if (!isset($config->column, $config->value)) {
            throw ActionConfigurationException::create($this, 'Could not delete CSV record, incomplete configuration.');
        }

        if (!is_writable(Path::get('~theme/cache'))) {
            throw ActionConfigurationException::create($this, 'Cache folder is not writable.');
        }

        $csv = self::getReaderInstance($config);

        $outputPath = Path::get('~theme/cache/yooessentials') . '/' . uniqid() . '-' . Path::basename($config->file);
        $outputCsv = self::copyCsvWithoutContent($csv, $outputPath, $config);

        $rowDeleted = false;
        foreach ($csv->getRecords() as $record) {
            $record = self::trimData($record);

            if ($rowDeleted) {
                $outputCsv->insertOne($record);

                continue;
            }

            if (isset($record[$config->column]) && $record[$config->column] === $config->value) {
                $rowDeleted = true;

                continue;
            }

            $outputCsv->insertOne($record);
        }

        File::rename($outputPath, self::resolveCsvPath($config));

        return $next(
            $response->withDataLog([
                self::NAME => ['config' => $config]
            ])
        );
    }
}
