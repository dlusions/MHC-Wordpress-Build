<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Csv;

use RuntimeException;
use ZOOlanders\YOOessentials\Form\Action\SaveToAction;
use ZOOlanders\YOOessentials\Form\Action\ActionConfigurationException;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;

class CsvUpsertAction extends SaveToAction
{
    use InteractsWithCsv;

    public const NAME = 'csv-record-upsert';

    public function __invoke(FormSubmissionResponse $response, callable $next): FormSubmissionResponse
    {
        $config = (object) $this->getConfig();
        $data = $this->resolveData($config->content ?? []);

        try {
            $headers = self::getCsvHeader($config);
            $data = self::sortDataFromHeaders($headers, $data);
            $csv = self::getWriterInstance($config);
        } catch (RuntimeException $e) {
            throw ActionConfigurationException::create($this, $e->getMessage(), $e);
        }

        $csv->insertOne($data);

        return $next(
            $response->withDataLog([
                self::NAME => ['config' => $config, 'data' => $data]
            ])
        );
    }
}
