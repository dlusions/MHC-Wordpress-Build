<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Airtable;

use function YOOtheme\app;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Api\Airtable\AirtableApi;
use ZOOlanders\YOOessentials\Api\Airtable\AirtableApiInterface;

class AirtableController
{
    public const GET_BASES_ENDPOINT = 'yooessentials/form/action/airtable/bases';
    public const GET_TABLES_ENDPOINT = 'yooessentials/form/action/airtable/tables';
    public const GET_FIELDS_ENDPOINT = 'yooessentials/form/action/airtable/fields';

    public function getBases(Request $request, Response $response)
    {
        $form = $request->getParam('form');
        $auth = $form['auth'] ?? null;

        try {
            if (!$auth) {
                throw new \Exception('Authentication must be specified.');
            }

            $bases = self::api($auth)->listBases();

            $result = array_map(fn ($base) => [
                'text' => $base['name'],
                'value' => $base['id'],
                'meta' => $base['id'],
            ], $bases);

            return $response->withJson($result, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function getTables(Request $request, Response $response)
    {
        $form = $request->getParam('form');
        $auth = $form['auth'] ?? null;
        $base = $form['base'] ?? null;

        try {
            if (!$auth) {
                throw new \Exception('Auth must be specified.');
            }

            if (!$base) {
                throw new \Exception('Base must be specified.');
            }

            $tables = self::api($auth)->listTables($base);

            $result = array_map(fn ($table) => [
                'text' => $table['name'],
                'value' => $table['id'],
                'meta' => $table['description'] ?? '',
            ], $tables);

            return $response->withJson($result, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function getFields(Request $request, Response $response)
    {
        $form = $request->getParam('form');
        $auth = $form['auth'] ?? null;
        $base = $form['base'] ?? null;
        $table = $form['table'] ?? null;

        try {
            if (!$auth) {
                throw new \Exception('Auth must be specified.');
            }

            if (!$base) {
                throw new \Exception('Base must be specified.');
            }

            if (!$table) {
                throw new \Exception('Table must be specified.');
            }

            $table = self::api($auth)->getTable($base, $table);

            $result = array_map(fn ($field) => [
                'id' => $field['id'],
                'title' => $field['name'],
                'type' => AirtableApi::castType($field['type']),
                'meta' => $field['type'],
            ], $table['fields'] ?? []);

            return $response->withJson($result, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    protected static function api(string $auth): ?AirtableApiInterface
    {
        $auth = app(AuthManager::class)->auth($auth);

        if (!$auth) {
            throw new \Exception('Invalid Auth.');
        }

        return app(AirtableApiInterface::class)->withAccessToken($auth->accessToken);
    }
}
