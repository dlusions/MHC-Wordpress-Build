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

class FacebookPagePostsQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData, HasApiRequest;

    public const NAME = 'pagePosts';
    public const LABEL = 'Page Posts';
    public const DESCRIPTION = 'List of page posts';

    public static function getCacheKey(): string
    {
        return 'facebook-page-posts';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => [
                        'listOf' => FacebookPostType::NAME,
                    ],

                    'args' => [
                        'type' => [
                            'type' => 'String',
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
                                'type' => 'select',
                                'label' => 'Type',
                                'default' => FacebookApi::DEFAULT_POST_TYPE,
                                'options' => [
                                    'Feed posts' => 'feed',
                                    'Published posts' => 'published_posts',
                                    'Published and unpublished posts' => 'posts',
                                    'Scheduled posts' => 'scheduled_posts',
                                    // 'Visitor created posts' => 'visitor_posts',
                                    // 'Ads created posts' => 'ads_posts',
                                ],
                                'description' => 'The type of posts to fetch. Notice that the default feed type will fetch all posts, including unpublished as well as created by visitors.',
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

        $posts = self::resolveFromCache($source, $args, fn () => $api->posts($source->pageId(), $args));

        return $posts;
    }
}
