<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleBusinessProfile\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class GoogleBusinessProfilePostType extends GenericType
{
    public const NAME = 'GoogleBusinessProfilePost';
    public const LABEL = 'Post';

    public function config(): array
    {
        return [
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
            'fields' => [
                'searchUrl' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'URL',
                    ],
                ],
                'topicType' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Topic Type',
                    ],
                ],
                'summary' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Summary',
                        'filters' => ['limit'],
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => static::class . '::resolveSummary',
                        ],
                    ],
                ],
                'primaryMedia' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Primary Media URL',
                    ],
                    'extensions' => [
                        'call' => [
                            'func' => static::class . '::resolvePrimaryMedia',
                        ],
                    ],
                ],
                'languageCode' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Language',
                    ],
                ],
                'createTime' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Created At',
                        'filters' => ['date'],
                    ],
                ],
                'updateTime' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Updated At',
                        'filters' => ['date'],
                    ],
                ],
                'offer' => [
                    'type' => GoogleBusinessProfilePostOfferType::NAME,
                    'metadata' => [
                        'label' => 'Offer',
                    ],
                ],
                'media' => [
                    'type' => [
                        'listOf' => GoogleBusinessProfileMediaType::NAME,
                    ],
                    'metadata' => [
                        'label' => 'Media',
                    ],
                ],
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                    ],
                ],
            ],
        ];
    }

    public static function resolveSummary(array $post): string
    {
        return nl2br($post['summary'] ?? '');
    }

    public static function resolvePrimaryMedia(array $post): ?string
    {
        $name = sha1($post['name'] ?? '');
        $url = $post['media'][0]['googleUrl'] ?? '';

        if (!$url) {
            return null;
        }

        return Util\File::cacheMedia($url, "gbp-post-media-$name");
    }
}
