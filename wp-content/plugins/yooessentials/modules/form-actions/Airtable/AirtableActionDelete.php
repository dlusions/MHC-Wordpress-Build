<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Airtable;

use ZOOlanders\YOOessentials\Form\Action\StandardAction;
use ZOOlanders\YOOessentials\Form\Action\ActionRuntimeException;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;

class AirtableActionDelete extends StandardAction
{
    use InteractsWithRecords;

    public const NAME = 'airtable-record-delete';

    public function __invoke(FormSubmissionResponse $response, callable $next): FormSubmissionResponse
    {
        try {
            self::deleteRecord($this);
        } catch (\Throwable $e) {
            throw ActionRuntimeException::create($this, $e->getMessage(), $e);
        }

        return $next(
            $response->withDataLog([
                self::NAME => [
                    'config' => $this->getConfig()
                ],
            ])
        );
    }
}
