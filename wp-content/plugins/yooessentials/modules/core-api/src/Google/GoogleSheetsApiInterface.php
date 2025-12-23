<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Google;

interface GoogleSheetsApiInterface
{
    public function spreadsheet(string $spreadsheetId, array $args = []): array;

    public function sheets(string $spreadsheetId, array $fields = ['sheetId', 'title'], array $params = []): array;

    public function values(string $spreadsheetId, string $range, array $args = []): array;

    public function append(string $spreadsheetId, array $values, string $range, array $params = []): array;

    public function deleteRow(string $spreadsheetId, string $sheetId, int $rowIndex): array;
}
