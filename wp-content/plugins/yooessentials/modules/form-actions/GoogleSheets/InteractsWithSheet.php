<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\GoogleSheets;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Api\Google\GoogleSheetsApi;
use ZOOlanders\YOOessentials\Api\Google\GoogleSheetsApiInterface;
use ZOOlanders\YOOessentials\Auth\AuthManager;

trait InteractsWithSheet
{
    protected static function api(string $authId): ?GoogleSheetsApiInterface
    {
        $auth = app(AuthManager::class)->auth($authId);

        if (!$auth) {
            throw new \RuntimeException('Invalid Account.');
        }

        /** @var GoogleSheetsApi $api */
        $api = app(GoogleSheetsApiInterface::class);

        return $api->withAccessToken($auth->accessToken());
    }

    private static function saveSheet(array $data, object $config): void
    {
        $inputOptions = [
            'INPUT_TYPE_RAW' => GoogleSheetsApi::INPUT_TYPE_RAW,
            'INPUT_TYPE_USER_ENTERED' => GoogleSheetsApi::INPUT_TYPE_USER_ENTERED,
        ];

        $sheet = $config->sheet_name ?? '';
        $sheetId = $config->sheet_id;
        $valueInput = $config->value_input ?? 'INPUT_TYPE_RAW';

        $header = self::getSheetHeader($config->account, $sheetId, $sheet);
        $interval = self::interval($sheet, $header);

        $data = self::sortDataFromHeaders($header, $data);

        $params = [
            'valueInputOption' => $inputOptions[$valueInput],
        ];

        self::api($config->account)->append($sheetId, array_values($data), $interval, $params);
    }

    private static function getSheetHeader(string $account, string $sheetId, string $sheetName)
    {
        if (!$account) {
            throw new \RuntimeException('Account must be specified.');
        }

        if (!$sheetId) {
            throw new \RuntimeException('Spreadsheet must be specified.');
        }

        if ($sheetName) {
            $header = self::api($account)->values($sheetId, "{$sheetName}!1:1")['values'][0] ?? [];
        } else {
            $header = self::api($account)->values($sheetId, '1:1')['values'][0] ?? [];
        }

        if (!$header) {
            throw new \RuntimeException('No columns found. At least one column has to be set in the header.');
        }

        return array_map(
            function (string $headerCell, int $index) {
                $headerCell = trim($headerCell);
                if (strlen($headerCell) === 0) {
                    throw new \RuntimeException(
                        sprintf('Column at position %s is missing a header. All columns must have a header.', ++$index)
                    );
                }

                return $headerCell;
            },
            $header,
            array_keys($header)
        );
    }

    private function findRowIndex(string $account, string $spreadsheetId, string $sheetName, string $column, string $value): ?int
    {
        $range = "{$sheetName}!{$column}1:{$column}";

        $response = self::api($account)->values($spreadsheetId, $range);
        $rows = $response['values'] ?? [];

        foreach ($rows as $rowIndex => $row) {
            if (isset($row[0]) && $row[0] === $value) {
                return $rowIndex;
            }
        }

        return null;
    }

    private static function interval(string $sheet, array $headers): string
    {
        $endColumn = 'A';

        for ($i = 1; $i < count($headers); $i++) {
            $endColumn = ++$endColumn;
        }

        return "{$sheet}!A1:{$endColumn}1";
    }
}
