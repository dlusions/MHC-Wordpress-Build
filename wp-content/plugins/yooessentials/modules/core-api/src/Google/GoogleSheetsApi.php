<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Google;

use YOOtheme\Arr;

class GoogleSheetsApi extends GoogleApi implements GoogleSheetsApiInterface
{
    /**
     * @see https://developers.google.com/sheets/api/reference/rest/v4/ValueInputOption
     */
    public const INPUT_TYPE_RAW = 'RAW';
    public const INPUT_TYPE_USER_ENTERED = 'USER_ENTERED';

    protected string $apiEndpoint = 'https://sheets.googleapis.com/v4';

    public function spreadsheet(string $spreadsheetId, array $params = []): array
    {
        return $this->get("spreadsheets/$spreadsheetId", $params);
    }

    public function sheetId(string $spreadsheetId, string $sheetName): int
    {
        $spreadsheet = $this->spreadsheet($spreadsheetId);
        $sheets = $spreadsheet['sheets'] ?? [];
        $sheet = Arr::find($sheets, fn ($sheet) => $sheet['properties']['title'] === $sheetName);

        return $sheet['properties']['sheetId'] ?? 0;
    }

    public function sheets(string $spreadsheetId, array $fields = ['sheetId', 'title'], array $params = []): array
    {
        return $this->spreadsheet(
            $spreadsheetId,
            array_merge(
                [
                    'fields' => 'sheets.properties(' . implode(',', $fields) . ')',
                ],
                $params
            )
        )['sheets'] ?? [];
    }

    public function values(string $spreadsheetId, string $range, array $params = []): array
    {
        $range = urlencode($range);

        return $this->get("spreadsheets/$spreadsheetId/values/$range", $params);
    }

    public function append(string $spreadsheetId, array $values, string $range, array $params = []): array
    {
        $body = [
            'range' => $range,
            'values' => [$values],
        ];

        $params += ['valueInputOption' => self::INPUT_TYPE_RAW];

        $range = urlencode($range);

        $url = "spreadsheets/$spreadsheetId/values/$range:append?" . http_build_query($params, '', '&');

        return $this->post($url, $body);
    }

    public function deleteRow(string $spreadsheetId, string $sheetName, int $rowIndex): array
    {
        $requests = [
            'deleteDimension' => [
                'range' => [
                    'sheetId' => $this->sheetId($spreadsheetId, $sheetName),
                    'dimension' => 'ROWS',
                    'startIndex' => $rowIndex,
                    'endIndex' => $rowIndex + 1,
                ],
            ],
        ];

        $body = [
            'requests' => [$requests],
        ];

        $url = "spreadsheets/$spreadsheetId:batchUpdate";

        return $this->post($url, $body);
    }
}
