<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Csv;

use YOOtheme\Str;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class CsvActionController
{
    use InteractsWithCsv;

    public const DOWNLOAD_CSV_URL = '/yooessentials/form-download-csv';
    public const GET_COLUMNS_ENDPOINT = 'yooessentials/forms/action/csv/columns';

    public function getColumns(Request $request, Response $response)
    {
        $form = $request->getParam('form') ?? [];

        try {
            $columns = [];
            $header = self::getCsvHeader((object) $form);

            foreach ($header as $i => $col) {
                $columns[] = [
                    'id' => $col,
                    'title' => Str::titleCase($col),
                ];
            }
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson($columns);
    }
}
