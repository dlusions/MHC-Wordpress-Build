<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials;

use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Database\Database;
use ZOOlanders\YOOessentials\Util\Prop;
use function YOOtheme\app;

abstract class AbstractDatabaseManager
{
    protected AuthManager $authManager;

    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    abstract public function createDatabaseFromOptions(array $options): Database;

    public function initialize(array $options): Database
    {
        $isExternal = $options['external'] ?? false;
        $database = $options['database'] ?? false;
        $connection = $options['connection'] ?? false;

        if (!$isExternal && !$database) {
            return app(Database::class);
        }

        // support for local connection with custom database
        if (!$isExternal && $database) {
            $options = Arr::pick($options, ['database']);
        }

        if ($isExternal && $connection) {
            $auth = $this->authManager->auth($connection);
            $options = $auth ? Arr::merge(Prop::filterByPrefix($auth->toArray(), 'db_'), Arr::pick($options, ['database', 'external'])) : $options;
        }

        return $this->createDatabaseFromOptions($options);
    }

    public function getTableColumnsFromDb(Database $db, string $table): array
    {
        $columns = $db->fetchAll('SHOW FULL COLUMNS FROM ' . $table);

        $data = [];
        foreach ($columns as $column) {
            $field = $column['Field'] ?? null;
            $type = $column['Type'] ?? 'String';

            if ($field) {
                $data[$field] = $type;
            }
        }

        return $data;
    }

    public function convertSqlTypeToSchemaType(string $type): string
    {
        switch ($type) {
            case 'int':
                return 'Int';
            case 'string':
            case 'String':
            case 'varchar':
            case 'text':
            case 'longtext':
            case 'mediumtext':
            case 'char':
            default:
                return 'String';
        }
    }

    public function getSchemaFiltersFromSqlType(string $type): array
    {
        switch ($type) {
            case 'datetime':
            case 'date':
                return ['date'];
            case 'string':
            case 'String':
            case 'varchar':
            case 'text':
            case 'longtext':
            case 'mediumtext':
            case 'char':
                return ['limit'];
        }

        return [];
    }
}
