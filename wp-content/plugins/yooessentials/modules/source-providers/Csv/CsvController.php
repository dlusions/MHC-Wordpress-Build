<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Csv;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Vendor\League\Csv\Statement;

class CsvController
{
    /** @var string */
    public const PRESAVE_ENDPOINT = 'yooessentials/source/csv';

    public function presave(Request $request, Response $response)
    {
        $form = $request->getParam('form');
        $file = $form['file'] ?? null;

        if (!$file) {
            return $response->withJson('Missing CSV File Path.', 400);
        }

        try {
            $csv = (new CsvSource($form))->csv();
            $records = Statement::create()->process($csv);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        if (empty($records->fetchOne(0))) {
            return $response->withJson('CSV File Records are missing.', 400);
        }

        return $response->withJson(200);
    }
}
