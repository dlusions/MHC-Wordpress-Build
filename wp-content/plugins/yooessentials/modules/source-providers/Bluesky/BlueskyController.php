<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Bluesky;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;

class BlueskyController
{
    use HasApiRequest, LoadsSourceFromArgs;

    public const PRESAVE_ENDPOINT = 'yooessentials/source/bluesky';
    public const ACTORS_ENDPOINT = 'yooessentials/source/bluesky/actors';
    public const ACTORS_LISTS_ENDPOINT = 'yooessentials/source/bluesky/actor/lists';

    public function presave(Request $request, Response $response)
    {
        $form = $request->getParam('form') ?? [];
        $actor = $form['actor'] ?? null;

        if (!$actor) {
            return $response->withJson('Actor must be specified.', 400);
        }

        return $response->withJson(200);
    }

    public function actors(Request $request, Response $response)
    {
        $query = $request->getParam('query') ?? '';

        if (empty($query)) {
            return $response->withJson([], 200);
        }

        try {
            $actors = self::api()->searchActors([
                'q' => $query
            ]);

            $result = array_map(fn ($actor) => [
                'value' => $actor['handle'],
                'text' => $actor['displayName'] ?? $actor['handle'],
                'meta' => '@' . $actor['handle']
            ], $actors);

            return $response->withJson($result, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function actorLists(Request $request, Response $response)
    {
        try {
            /** @var BlueskySource */
            $source = self::loadSource($request->getParsedBody(), BlueskySource::class);

            $actor = $source->config('actor') ?? '';
            $lists = self::api()->getLists($actor);

            $result = array_map(fn ($list) => [
                'value' => $list['uri'],
                'text' => $list['name'],
                'meta' => $list['description'] ?? ''
            ], $lists);

            return $response->withJson($result, 200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }
}
