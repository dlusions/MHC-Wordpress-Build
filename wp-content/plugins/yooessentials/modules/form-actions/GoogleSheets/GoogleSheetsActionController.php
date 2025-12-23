<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\GoogleSheets;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use YOOtheme\Str;
use ZOOlanders\YOOessentials\Api\Google\GoogleDriveApiInterface;
use ZOOlanders\YOOessentials\Auth\AuthManager;

class GoogleSheetsActionController
{
    use InteractsWithSheet;

    public const GET_SPREADSHEET_LIST_ENDPOINT = 'yooessentials/forms/action/google-sheets/spreadsheets';
    public const GET_SPREADSHEET_SHEETS_ENDPOINT = 'yooessentials/forms/action/google-sheets/sheets';
    public const GET_SHEET_COLUMNS_ENDPOINT = 'yooessentials/forms/action/google-sheets/columns';

    public function getSpreadsheetList(
        Request $request,
        Response $response,
        GoogleDriveApiInterface $googleDriveApi,
        AuthManager $authManager
    ) {
        $form = $request->getParam('form');

        $account = $form['account'] ?? '';
        $query = $request->getParam('query') ?? '';

        try {
            if (!$account) {
                throw new \RuntimeException('Account must be specified.');
            }

            $options = [
                'q' => 'mimeType = "application/vnd.google-apps.spreadsheet"',
                'orderBy' => 'modifiedTime',
                'fields' => 'files(id,name,description)',
            ];

            if ($query) {
                $options['q'] .= " and name contains \"$query\"";
            }
            $auth = $authManager->auth($account);
            $api = $googleDriveApi->withAccessToken($auth->accessToken());
            $files = $api->files($options)['files'] ?? [];

            $items = array_map(fn ($sheet) => [
                'text' => $sheet['name'],
                'value' => $sheet['id'],
                'meta' => $sheet['id'],
            ], $files);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson($items, 200);
    }

    public function getSpreadsheetSheetsList(Request $request, Response $response)
    {
        $form = $request->getParam('form');

        $account = $form['account'] ?? '';
        $sheetId = $form['sheet_id'] ?? null;

        try {
            if (!$account) {
                throw new \RuntimeException('Account must be specified.');
            }

            if (!$sheetId) {
                throw new \RuntimeException('Spreadsheet must be specified.');
            }

            $sheets = self::api($account)->sheets($sheetId);

            $sheets = array_map(fn ($sheet) => [
                'text' => $sheet['properties']['title'],
                'value' => $sheet['properties']['title'],
            ], $sheets);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson($sheets, 200);
    }

    public function getSheetColumns(Request $request, Response $response)
    {
        $form = $request->getParam('form') ?? [];

        $account = $form['account'] ?? '';
        $sheetId = $form['sheet_id'] ?? '';
        $sheetName = $form['sheet_name'] ?? '';

        try {
            $header = self::getSheetHeader($account, $sheetId, $sheetName);

            $columns = array_map(fn (string $headerCell) => [
                'id' => $headerCell,
                'title' => Str::titleCase($headerCell),
            ], $header);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson($columns, 200);
    }
}
