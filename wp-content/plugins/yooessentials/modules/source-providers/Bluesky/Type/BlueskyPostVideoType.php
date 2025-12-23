<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Bluesky\Type;

use YOOtheme\Url;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class BlueskyPostVideoType extends GenericType
{
    public const NAME = 'BlueskyPostVideo';
    public const LABEL = 'Post Video';

    public function config(): array
    {
        return [
            'fields' => [
                'cid' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'CID',
                        'description' => 'Content Identifier for the video',
                    ],
                ],
                'thumbnail' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Thumbnail URL',
                        'description' => 'URL of the thumbnail image',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveThumbnail',
                    ],
                ],
                'playlist' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Playlist',
                        'description' => 'URL of the video playlist, or a single video',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolvePlaylist',
                    ],
                ],
                'height' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Height',
                        'description' => 'Height of the video',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveHeight',
                    ],
                ],
                'width' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Width',
                        'description' => 'Width of the video',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveWidth',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function resolveThumbnail(array $video): string
    {
        $id = '';
        $url = $video['thumbnail'] ?? '';

        if (Url::isValid($url)) {
            return Util\File::cacheMedia($url, "bluesky-post-thumbnail-$id");
        }

        return '';
    }

    public static function resolvePlaylist(array $video): string
    {
        $url = $video['playlist'] ?? '';

        if (Url::isValid($url)) {
            $id = $video['cid'];

            return Util\File::cacheMedia($url, "bluesky-post-video-$id");
        }

        return '';
    }

    public static function resolveHeight(array $video): int
    {
        return $video['aspectRatio']['height'] ?? 0;
    }

    public static function resolveWidth(array $video): int
    {
        return $video['aspectRatio']['width'] ?? 0;
    }
}
