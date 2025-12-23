<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Airtable;

interface AirtableApiInterface
{
    public function listBases(string $offset = ''): array;

    public function listTables(string $baseId): array;

    public function getTable(string $baseId, string $tableId): array;

    public function getRecord(string $baseId, string $tableId, string $recordId): array;

    public function listRecords(string $baseId, string $tableId, array $filters = []): array;

    public function createRecords(string $baseId, string $tableId, array $data): array;

    public function updateRecord(string $baseId, string $tableId, string $recordId, array $data, bool $replace): array;

    public function deleteRecord(string $baseId, string $tableId, string $recordId): array;
}
