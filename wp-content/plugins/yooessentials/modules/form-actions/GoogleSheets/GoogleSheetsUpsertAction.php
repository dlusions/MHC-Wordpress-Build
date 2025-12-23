<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\GoogleSheets;

use RuntimeException;
use ZOOlanders\YOOessentials\Form\Action\ActionRuntimeException;
use ZOOlanders\YOOessentials\Form\Action\SaveToAction;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;

class GoogleSheetsUpsertAction extends SaveToAction
{
    use InteractsWithSheet;

    public const NAME = 'google-sheets-record-upsert';

    protected const DISABLED_VALUE = '';

    public function __invoke(FormSubmissionResponse $response, callable $next): FormSubmissionResponse
    {
        $config = (object) $this->getConfig();
        $data = $this->resolveData($config->content ?? []);

        try {
            self::saveSheet($data, $config);
        } catch (RuntimeException $e) {
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
