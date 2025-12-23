<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GooglePhotos\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class GooglePhotosAlbumType extends GenericType
{
    public const NAME = 'GooglePhotosAlbum';
    public const LABEL = 'Photos Album';

    public function config(): array
    {
        return [
            'fields' => [
                'title' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Title',
                        'filters' => ['limit'],
                    ],
                ],
                'coverPhotoBaseUrl' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Cover Photo URL',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveCoverPhotoUrl',
                    ],
                ],
                'mediaItemsCount' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Media Count',
                    ],
                ],
                'id' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function resolveCoverPhotoUrl(array $album): string
    {
        $id = $album['id'] ?? '';
        $url = $album['coverPhotoBaseUrl'] ?? '';

        if ($id && $url) {
            return Util\File::cacheMedia($url, "google-photos-album-{$id}");
        }

        return '';
    }
}
