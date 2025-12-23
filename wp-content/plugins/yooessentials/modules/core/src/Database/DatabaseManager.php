<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Database;

interface DatabaseManager
{
    public function initialize(array $config): Database;

    public function getTableColumnsFromDb(Database $db, string $table): array;

    public function convertSqlTypeToSchemaType(string $type): string;

    public function getSchemaFiltersFromSqlType(string $type): array;

    public function type(): string;

    public function serverVersion(): string;

    public function collation(): string;

    public function connectionCollation(): string;
}
