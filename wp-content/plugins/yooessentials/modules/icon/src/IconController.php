<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Icon;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class IconController
{
    public function getIcons(Request $request, Response $response, IconApi $api)
    {
        $offset = (int) $request->getParam('offset', 0);
        $length = (int) $request->getParam('length', 200);
        $search = $request->getParam('search');
        $group = $request->getParam('group');
        $collection = $request->getParam('collection');

        try {
            $result = $api->fetchIcons($offset, $length, $search, $collection, $group);
        } catch (\Throwable $e) {
            $request->abort(400, $e->getMessage());
        }

        return $response->withJson($result);
    }

    public function addCollection(Request $request, Response $response, IconApi $api, IconLoader $loader)
    {
        $name = $request->getParam('name');

        try {
            if (!$name) {
                throw new \Exception('Missing collection name.');
            }

            $api->loadCollection($name);

            $collections = $loader->getCollections();
        } catch (\Throwable $e) {
            $request->abort(400, $e->getMessage());
        }

        return $response->withJson($collections);
    }

    public function removeCollection(Request $request, Response $response, IconApi $api, IconLoader $loader)
    {
        $name = $request->getParam('name');

        try {
            if (!$name) {
                throw new \Exception('Missing collection name.');
            }

            $api->removeCollection($name);

            $collections = $loader->getCollections();
            unset($collections[$name]->data['installed']);
        } catch (\Throwable $e) {
            $request->abort(400, $e->getMessage());
        }

        return $response->withJson($collections);
    }
}
