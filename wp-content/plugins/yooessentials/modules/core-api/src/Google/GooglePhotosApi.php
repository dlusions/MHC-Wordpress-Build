<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Google;

use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Source\Provider\GooglePhotos\GooglePhotosSource;

// https://developers.google.com/photos
class GooglePhotosApi extends GoogleApi implements GooglePhotosApiInterface
{
    protected string $apiEndpoint = 'https://photoslibrary.googleapis.com/v1';

    public function albums(array $args = []): array
    {
        $result = $this->get('albums', $args);

        return $result['albums'] ?? [];
    }

    public function album(string $albumId): array
    {
        return $this->get("albums/$albumId");
    }

    public function mediaItemsSearch(array $args = []): array
    {
        // We should fetch +offset so if there is an offset of 1, and we want to fetch 20,
        // we fetch 21 and shave off 1 from the head of the lost
        $offset = $args['offset'] ?? 0;
        $limit = $args['pageSize'] ?? GooglePhotosSource::PAGE_SIZE_DEFAULT;

        unset($args['offset']);

        if ($offset >= $limit) {
            $offset = 0;
        }

        $resultsToFetch = $limit + $offset;
        $result = $this->fetchAlbumMedias($args, $resultsToFetch, null);
        $medias = $result['mediaItems'] ?? [];
        $medias = array_slice($medias, $offset);
        $originalLimit = $limit;

        while ($originalLimit > count($medias)) {
            // iterate again, with the next page
            $next = $result['nextPageToken'] ?? null;

            if (!$next) {
                return $medias;
            }

            // Next set
            $limit = $originalLimit - count($medias);

            if ($limit <= 0) {
                return $medias;
            }

            $result = $this->fetchAlbumMedias($args, $originalLimit, $next);
            $medias = array_merge($medias, $result['mediaItems'] ?? []);
        }

        return array_slice($medias, 0, $originalLimit);
    }

    protected function fetchAlbumMedias(array $params, int $limit = GooglePhotosSource::PAGE_SIZE_DEFAULT, ?string $pageToken = null): array
    {
        $mediaType = $params['media_type'] ?? 'all';
        $params = array_filter(array_merge(Arr::pick($params, [
            'albumId',
            'filters',
        ]), [
            'pageSize' => $limit,
            'pageToken' => $pageToken,
        ]));

        // To show in date ASC, we need to totally remove the orderBy param
        if (isset($params['orderBy']) && $params['orderBy'] === 'MediaMetadata.creation_time') {
            unset($params['orderBy']);
        }

        $result = $this->post('mediaItems:search', $params);

        return $this->filterMedias($result, $mediaType);
    }

    protected function filterMedias(array $medias, string $filterMedia): array
    {
        $data = Arr::filter($medias['mediaItems'] ?? [], function (array $media) use ($filterMedia) {
            if ($filterMedia === 'all') {
                return true;
            }

            $mediaType = isset($media['mediaMetadata']['video']) ? 'videos' : 'images';

            return $mediaType === $filterMedia;
        });

        $medias['mediaItems'] = $data;

        return $medias;
    }
}
