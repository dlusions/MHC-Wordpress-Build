<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\GoogleSheets;

use ZOOlanders\YOOessentials\Form\Action\StandardAction;
use ZOOlanders\YOOessentials\Form\Action\ActionConfigurationException;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;

class GoogleSheetsDeleteAction extends StandardAction
{
    use InteractsWithSheet;

    public const NAME = 'google-sheets-record-delete';

    public function __invoke(FormSubmissionResponse $response, callable $next): FormSubmissionResponse
    {
        $config = (object) $this->getConfig();

        if (empty($config->column)) {
            $config->column = 'A';
        }

        if (!isset($config->value)) {
            throw ActionConfigurationException::create($this, 'Could not delete sheet record, incomplete configuration.');
        }

        $rowIndex = self::findRowIndex($config->account, $config->sheet_id, $config->sheet_name, $config->column, $config->value);

        self::api($config->account)->deleteRow($config->sheet_id, $config->sheet_name, $rowIndex);

        return $next(
            $response->withDataLog([
                self::NAME => [
                    'config' => $config
                ],
            ])
        );
    }
}
