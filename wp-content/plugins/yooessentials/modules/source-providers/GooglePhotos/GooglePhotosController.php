<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GooglePhotos;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;

class GooglePhotosController
{
    use LoadsSourceFromArgs;

    /** @var string */
    public const PRESAVE_ENDPOINT = 'yooessentials/source/google/photos';
    public const GET_ALBUMS_ENDPOINT = 'yooessentials/source/google/photos/albums';

    public function presave(Request $request, Response $response)
    {
        $form = $request('form');
        $account = $form['account'] ?? null;

        if (!$account) {
            return $response->withJson('Account must be specified.', 400);
        }

        return $response->withJson(200);
    }

    public function albums(Request $request, Response $response)
    {
        $sourceId = $request->getParam('source_id') ?? [];
        $query = $request->getParam('query') ?? null;

        try {
            $source = self::loadSource([
                'source_id' => $sourceId
            ], GooglePhotosSource::class);

            $albums = $source->api()->albums();

            $result = array_map(fn ($album) => [
                'text' => $album['title'],
                'value' => $album['id'],
                'meta' => sprintf('Total Media (%s)', $album['mediaItemsCount']),
            ], $albums);

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
