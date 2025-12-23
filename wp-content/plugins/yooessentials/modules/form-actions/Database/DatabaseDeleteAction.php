<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Database;

use ZOOlanders\YOOessentials\Database\Database;
use ZOOlanders\YOOessentials\Form\Action\ActionConfigurationException;
use ZOOlanders\YOOessentials\Form\Action\ActionRuntimeException;
use ZOOlanders\YOOessentials\Form\Action\StandardAction;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;

class DatabaseDeleteAction extends StandardAction
{
    use HasDatabase;
    use PreparesDbFilters;

    public const NAME = 'database-record-delete';

    public function __invoke(FormSubmissionResponse $response, callable $next): FormSubmissionResponse
    {
        $config = (object) $this->getConfig();

        $db = $this->db((array) $config);

        $this->deleteRecord($config, $db);

        return $next(
            $response->withDataLog([
                self::NAME => [
                    'config' => $config
                ],
            ])
        );
    }

    private function deleteRecord(object $config, Database $db)
    {
        if (empty($config->table_record ?? [])) {
            throw ActionConfigurationException::create($this, 'Could not delete database record, incomplete configuration.');
        }

        try {
            $count = $this->countRecords($config, $db);
        } catch (\Throwable $exception) {
            throw ActionRuntimeException::create($this, 'There was an error deleting the records: ' . $exception->getMessage());
        }

        if ($count > 1 && !($config->match_multiple ?? false)) {
            throw ActionRuntimeException::create($this, 'Could not delete database record, more than one match found.');
        }

        $result = $db->delete($config->table, $this->getFiltersFromConfig($config));

        if (!$result) {
            throw ActionRuntimeException::create($this, 'Could not delete database record.');
        }
    }
}
