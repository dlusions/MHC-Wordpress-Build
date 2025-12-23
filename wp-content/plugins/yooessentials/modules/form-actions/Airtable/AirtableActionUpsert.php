<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Airtable;

use ZOOlanders\YOOessentials\Form\Action\SaveToAction;
use ZOOlanders\YOOessentials\Form\Action\ActionRuntimeException;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;

class AirtableActionUpsert extends SaveToAction
{
    use InteractsWithRecords;

    public const NAME = 'airtable-record-upsert';

    protected const DISABLED_VALUE = '';

    public function __invoke(FormSubmissionResponse $response, callable $next): FormSubmissionResponse
    {
        $config = (object) $this->getConfig();
        $data = $this->resolveData($config->content ?? []);
        $typecast = $config->typecast ?? false;

        try {
            self::createOrUpdateRecord($this, $data, $typecast);
        } catch (\Throwable $e) {
            throw ActionRuntimeException::create($this, $e->getMessage(), $e);
        }

        return $next(
            $response->withDataLog([
                self::NAME => [
                    'config' => $config,
                    'data' => $data,
                ],
            ])
        );
    }
}
