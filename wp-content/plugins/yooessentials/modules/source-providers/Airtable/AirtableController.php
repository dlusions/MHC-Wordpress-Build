<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Airtable;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;

class AirtableController
{
    use LoadsSourceFromArgs;

    public const PRESAVE_ENDPOINT = 'yooessentials/source/airtable';
    public const GET_BASES_ENDPOINT = 'yooessentials/source/airtable/bases';
    public const GET_TABLES_ENDPOINT = 'yooessentials/source/airtable/tables';
    public const GET_VIEWS_ENDPOINT = 'yooessentials/source/airtable/views';

    public function presave(Request $request, Response $response)
    {
        $form = $request('form');
        $auth = $form['auth'] ?? null;
        $base = $form['base'] ?? null;
        $table = $form['table'] ?? null;

        try {
            if (!$auth) {
                throw new \Exception('Authentication must be specified.');
            }

            if (!$base) {
                throw new \Exception('A base must be specified.');
            }

            if (!$table) {
                throw new \Exception('A base table must be specified.');
            }

            return $response->withJson(200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function bases(Request $request, Response $response)
    {
        $form = $request->getParam('form');
        $query = $request->getParam('query') ?? null;

        try {
            /** @var AirtableSource $source */
            $source = self::loadSource($form, AirtableSource::class);

            $bases = $source->api()->listBases();

            $result = array_map(fn ($base) => [
                'text' => $base['name'],
                'value' => $base['id'],
                'meta' => $base['id'],
            ], $bases);

            if ($query) {
                $result = array_values(
                    array_filter($result, fn ($item) => (bool) preg_match('#.*' . $query . '.*#i', $item['text']))
                );
            }

            return $response->withJson($result, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function tables(Request $request, Response $response)
    {
        $form = $request->getParam('form');
        $query = $request->getParam('query') ?? null;
        $base = $form['base'] ?? null;

        try {
            if (!$base) {
                throw new \Exception('Base must be specified.');
            }

            /** @var AirtableSource $source */
            $source = self::loadSource($form, AirtableSource::class);

            $tables = $source->api()->listTables($base);

            $result = array_map(fn ($table) => [
                'text' => $table['name'],
                'value' => $table['id'],
                'meta' => $table['description'] ?? '',
            ], $tables);

            if ($query) {
                $result = array_values(
                    array_filter($result, fn ($item) => (bool) preg_match('#.*' . $query . '.*#i', $item['text']))
                );
            }

            return $response->withJson($result, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function views(Request $request, Response $response)
    {
        $query = $request->getParam('query') ?? null;

        try {
            /** @var AirtableSource $source */
            $source = self::loadSource($request->getParsedBody(), AirtableSource::class);

            $views = $source->getTable('views');

            $result = array_map(fn ($table) => [
                'text' => $table['name'],
                'value' => $table['id'],
                'meta' => $table['type'],
            ], $views);

            if ($query) {
                $result = array_values(
                    array_filter($result, fn ($item) => (bool) preg_match('#.*' . $query . '.*#i', $item['text']))
                );
            }

            return $response->withJson($result, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }
}
