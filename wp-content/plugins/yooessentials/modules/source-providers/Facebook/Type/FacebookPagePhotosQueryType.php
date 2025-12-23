<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Facebook\Type;

use ZOOlanders\YOOessentials\Api\Facebook\FacebookApi;
use ZOOlanders\YOOessentials\Api\Facebook\FacebookApiInterface;
use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\Facebook\FacebookSource;
use ZOOlanders\YOOessentials\Source\Provider\Facebook\HasApiRequest;

class FacebookPagePhotosQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData, HasApiRequest;

    public const NAME = 'pagePhotos';
    public const LABEL = 'Page Photos';
    public const DESCRIPTION = 'List of page photos';

    public static function getCacheKey(): string
    {
        return 'facebook-page-photos';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => [
                        'listOf' => FacebookPhotoType::NAME,
                    ],

                    'args' => [
                        'type' => [
                            'type' => 'String'
                        ],
                        'limit' => [
                            'type' => 'Int',
                        ],
                        'cache' => [
                            'type' => 'Int',
                        ],
                    ],

                    'metadata' => [
                        'group' => 'Essentials',
                        'label' => $this->label(),
                        'description' => $this->description(),
                        'fields' => [
                            'type' => [
                                'label' => 'Type',
                                'default' => FacebookApi::TYPE_UPLOADED,
                                'type' => 'select',
                                'options' => [
                                    'Uploaded' => FacebookApi::TYPE_UPLOADED,
                                    'Profile' => FacebookApi::TYPE_PROFILE,
                                ]
                            ],
                            'limit' => [
                                'label' => 'Limit',
                                'type' => 'yooessentials-number',
                                'description' => 'The maximum amount of posts to fetch.',
                                'default' => FacebookSource::MEDIA_LIMIT_DEFAULT,
                                'source' => true,
                                'attr' => [
                                    'max' => 100,
                                ],
                            ],

                            'cache' => $this->cacheField(),
                        ],
                    ],

                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolve',
                            'args' => [
                                'source_id' => $this->source->id(),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root, array $args): array
    {
        /** @var FacebookSource */
        $source = self::loadSource($args, FacebookSource::class);

        if (!$source) {
            return [];
        }

        /** @var ?FacebookApiInterface */
        $api = self::api($source->account());

        if (!$api) {
            return [];
        }

        return self::resolveFromCache($source, $args, fn () => $api->photos($source->pageId(), $args));
    }
}
