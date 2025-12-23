<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleSheets;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Api\Google\GoogleDriveApiInterface;

class GoogleSheetsController
{
    use LoadsSourceFromArgs;

    /**
     * @var string
     */
    public const PRESAVE_ENDPOINT = 'yooessentials/source/google-sheets';
    public const GET_SHEETS_ENDPOINT = 'yooessentials/source/google-sheets/sheets';
    public const GET_SPREADSHEETS_ENDPOINT = 'yooessentials/source/google-sheets/spreadsheets';

    public function presave(Request $request, Response $response)
    {
        $form = $request('form');
        $account = $form['account'] ?? null;
        $spreadsheetId = $form['sheet_id'] ?? null;

        if (!$account) {
            return $response->withJson('Account must be specified.', 400);
        }

        if (!$spreadsheetId) {
            return $response->withJson('Spreadsheet must be specified.', 400);
        }

        try {
            /** @var GoogleSheetsSource $source */
            $source = self::loadSource($form, GoogleSheetsSource::class);

            // check if spreadsheet id is valid
            $source->api()->spreadsheet($spreadsheetId);

            // check if sheet name is valid
            $sheetName = $form['sheet_name'] ?? null;
            $sheetNames = array_map(fn (array $sheet) => $sheet['properties']['title'], $source->api()->sheets($spreadsheetId));

            if (!empty($sheetName && !in_array($sheetName, $sheetNames))) {
                throw new \Exception('Invalid spreadsheet sheet name.');
            }

            // check if headers are set
            $source->headers();
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson(200);
    }

    public function spreadsheets(Request $request, Response $response, GoogleDriveApiInterface $googleDriveApi)
    {
        $form = $request->getParam('form');
        $search = $request->getParam('query') ?? null;

        $options = [
            'q' => 'mimeType = "application/vnd.google-apps.spreadsheet"',
            'orderBy' => 'modifiedTime',
            'fields' => 'files(id,name,description,driveId)',
        ];

        if ($search) {
            $options['q'] .= " and name contains \"$search\"";
        }

        try {
            /** @var GoogleSheetsSource $source */
            $source = self::loadSource($form, GoogleSheetsSource::class);

            $api = $googleDriveApi->withAccessToken($source->auth()->accessToken());

            $files = $api->files($options)['files'] ?? [];

            $result = array_map(function ($file) {
                $meta = $file['id'];
                if ($file['driveId'] ?? null) {
                    $meta .= ' - Drive Id: ' . $file['driveId'];
                }

                return [
                    'text' => $file['name'],
                    'value' => $file['id'],
                    'meta' => $meta,
                ];
            }, $files);

            return $response->withJson($result, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function sheets(Request $request, Response $response)
    {
        $form = $request->getParam('form');
        $spreadsheetId = $form['sheet_id'] ?? null;

        try {
            if (!$spreadsheetId) {
                throw new \Exception('Spreadsheet ID must be specified.');
            }

            /** @var GoogleSheetsSource $source */
            $source = self::loadSource($form, GoogleSheetsSource::class);

            $sheets = $source->api()->sheets($spreadsheetId);

            $result = array_map(fn ($sheet) => [
                'text' => $sheet['properties']['title'],
                'value' => $sheet['properties']['title'],
            ], $sheets);

            return $response->withJson($result, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }
}
