<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\TikTok\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Source\Provider\TikTok\TikTokHelper;
use ZOOlanders\YOOessentials\Util;

class TikTokVideoType extends GenericType
{
    public const NAME = 'TikTokVideo';
    public const LABEL = 'Video';

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
                'title_html' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Title HTML',
                        'filters' => ['limit'],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveTitle',
                    ],
                ],
                'video_description' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Description',
                        'filters' => ['limit'],
                    ],
                ],
                'video_description_html' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Description HTML',
                        'filters' => ['limit'],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveDescription',
                    ],
                ],
                'duration' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Duration (sec)',
                    ],
                ],
                'create_time' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Created At',
                        'filters' => ['date'],
                    ],
                ],
                'cover_image_url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Cover',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveCover',
                    ],
                ],
                'embed_link' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Embed Link',
                    ],
                ],
                'embed_html' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Embed HTML',
                    ],
                ],
                'share_url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Share URL',
                    ],
                ],
                'width' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Width',
                    ],
                ],
                'height' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Height',
                    ],
                ],
                'share_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Shares',
                    ],
                ],
                'like_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Likes',
                    ],
                ],
                'comment_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Comments',
                    ],
                ],
                'view_count' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Views',
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

    public static function resolveCover(array $video): string
    {
        $id = $video['id'] ?? '';
        $url = $video['cover_image_url'] ?? '';

        if (!$url || !$id) {
            return '';
        }

        return Util\File::cacheMedia($url, "tiktok-media-$id");
    }

    public static function resolveTitle(array $video): string
    {
        $title = $video['title'] ?? '';

        if (!$title) {
            return '';
        }

        return TikTokHelper::parseText($title);
    }

    public static function resolveDescription(array $video): string
    {
        $description = $video['video_description'] ?? '';

        if (!$description) {
            return '';
        }

        return TikTokHelper::parseText($description);
    }
}
