<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Instagram;

use ZOOlanders\YOOessentials\Util\Arr;

/**
 * @mixin InstagramApi|InstagramApiInterface
 */
trait IteratesOverMedias
{
    public function medias(string $userOrPageId, int $limit = 20, array $filters = []): array
    {
        // We should fetch +offset so if there is an offset of 1, and we want to fetch 20,
        // we fetch 21 and shave off 1 from the head of the lost
        $offset = $filters['offset'] ?? 0;

        $resultsToFetch = $limit + $offset;
        $result = $this->fetchMediaResults($userOrPageId, $resultsToFetch, null, $filters);
        $medias = $result['data'] ?? [];
        $medias = array_slice($medias, $offset);
        $originalLimit = $limit;

        while ($originalLimit > count($medias)) {
            // iterate again, with the next page
            $next = $result['paging']['cursors']['after'] ?? null;

            if (!$next) {
                return $medias;
            }

            // Next set
            $limit = $originalLimit - count($medias);

            if ($limit <= 0) {
                return $medias;
            }

            $result = $this->fetchMediaResults($userOrPageId, $limit, $next, $filters);
            $medias = array_merge($medias, $result['data'] ?? []);
        }

        return $medias;
    }

    protected function fetchMediaResults(
        string $userOrPageId,
        int $limit = 20,
        ?string $after = null,
        array $filters = []
    ): array {
        $fields = $this->getMediaFields();

        $medias = $this->get(
            "$userOrPageId/media",
            array_filter([
                'fields' => $fields,
                'limit' => $limit,
                'after' => $after,
                'since' => $filters['since'] ?? null,
                'until' => $filters['until'] ?? null,
            ])
        );

        return $this->filterMedias($medias, $filters);
    }

    protected function filterMedias(array $medias, array $filters): array
    {
        $data = Arr::filter($medias['data'] ?? [], fn (array $media) => $this->filterByMediaType($media, $filters['media_type'] ?? 'all'));

        $medias['data'] = $data;

        return $medias;
    }

    protected function filterByMediaType(array $media, string $filterMedia): bool
    {
        $mediaType = $media['media_type'] ?? InstagramMediaTypes::TYPE_IMAGE;

        switch ($filterMedia) {
            case 'videos':
                if ($mediaType !== InstagramMediaTypes::TYPE_VIDEO) {
                    return false;
                }

                break;

            case 'images':
                if (!in_array($mediaType, [InstagramMediaTypes::TYPE_IMAGE, InstagramMediaTypes::TYPE_ALBUM])) {
                    return false;
                }

                break;
        }

        return true;
    }

    abstract protected function getMediaFields(): string;

    abstract protected function getMediaMaxLimit(): int;
}
