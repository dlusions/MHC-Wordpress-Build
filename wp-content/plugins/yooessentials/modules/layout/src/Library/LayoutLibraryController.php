<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Layout\Library;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Layout\LayoutManager;

class LayoutLibraryController
{
    public const LAYOUT_LIBRARY_INDEX_ENDPOINT = 'yooessentials/layout/library';
    public const LAYOUT_LIBRARY_NODE_GET_ENDPOINT = 'yooessentials/layout/library/node';
    public const LAYOUT_LIBRARY_NODE_SAVE_ENDPOINT = 'yooessentials/layout/library/node/save';
    public const LAYOUT_LIBRARY_NODE_DELETE_ENDPOINT = 'yooessentials/layout/library/node/delete';

    public function getLibraryIndex(Request $request, Response $response, LayoutManager $manager)
    {
        try {
            $library = self::getLibraryFromRequest($request, $manager);
            $refresh = (bool) $request->getParam('refresh', 0);

            $result = $library->files($refresh);

            return $response->withJson($result);
        } catch (\Throwable $e) {
            $request->abort(400, $e->getMessage());
        }
    }

    public static function getNode(Request $request, Response $response, LayoutManager $manager)
    {
        $ids = $request->getParam('ids');

        if (!$ids) {
            $request->abort(400, 'Missing argument: ids');
        }

        try {
            $library = self::getLibraryFromRequest($request, $manager);

            $layouts = array_filter(
                array_map(fn ($id) => $library->read($id), $ids)
            );

            return $response->withJson($layouts);
        } catch (\Throwable $e) {
            $request->abort(400, $e->getMessage());
        }
    }

    public static function saveNode(Request $request, Response $response, LayoutManager $manager)
    {
        try {
            $node = self::getNodeFromRequest($request);
            $library = self::getLibraryFromRequest($request, $manager);
            $library->upload($node);
        } catch (\Throwable $e) {
            $request->abort(400, $e->getMessage());
        }

        return $response->withJson(200);
    }

    public static function deleteNodes(Request $request, Response $response, LayoutManager $manager)
    {
        try {
            $ids = array_filter($request->getParam('ids') ?? []);

            if (empty($ids)) {
                throw new \Exception('Missing Nodes argument');
            }

            $library = self::getLibraryFromRequest($request, $manager);

            foreach ($ids as $id) {
                $library->delete($id);
            }
        } catch (\Throwable $e) {
            $request->abort(400, $e->getMessage());
        }

        return $response->withJson(200);
    }

    /**
     * @param Request $request
     * @return array|object
     * @throws \Exception
     */
    protected static function getNodeFromRequest(Request $request)
    {
        $node = $request->getParam('node');

        if (!$node) {
            throw new \Exception('Missing Node argument');
        }

        return (object) $node;
    }

    protected static function getLibraryFromRequest(Request $request, LayoutManager $manager): LayoutLibrary
    {
        $libId = $request->getParam('library');

        if (!$libId) {
            throw new \Exception('Missing Library argument');
        }

        $library = $manager->library($libId);

        if (!$library) {
            throw new \Exception("Cannot load Layout Library with ID: $libId");
        }

        return $library;
    }
}
