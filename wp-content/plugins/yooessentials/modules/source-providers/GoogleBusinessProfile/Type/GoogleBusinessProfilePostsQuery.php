<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleBusinessProfile\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\GoogleBusinessProfile\GoogleBusinessProfileSource;

class GoogleBusinessProfilePostsQuery extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'posts';
    public const LABEL = 'Posts';
    public const DESCRIPTION = 'List of the profile posts';

    public static function getCacheKey(): string
    {
        return 'google-business-profile-posts';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => ['listOf' => GoogleBusinessProfilePostType::NAME],

                    'args' => [
                        'cache' => [
                            'type' => 'Int',
                        ],
                        'limit' => [
                            'type' => 'Int',
                        ],
                    ],

                    'metadata' => [
                        'group' => 'Essentials',
                        'label' => $this->label(),
                        'description' => $this->description(),
                        'fields' => [
                            'limit' => [
                                'label' => 'Quantity',
                                'description' => 'The maximum amount of posts to fetch.',
                                'type' => 'limit',
                                'default' => 20,
                                'source' => true,
                                'attrs' => [
                                    'min' => 1,
                                    'max' => 100,
                                ],
                            ],
                            'cache' => $this->cacheField(),
                        ],
                    ],

                    'extensions' => [
                        'call' => [
                            'func' => static::class . '::resolve',
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
        /** @var GoogleBusinessProfileSource */
        $source = self::loadSource($args, GoogleBusinessProfileSource::class);

        if (!$source || !$source->location) {
            return [];
        }

        return self::resolveFromCache($source, $args, fn () => $source->api()->posts(
            $source->businessAccount,
            $source->location,
            array_filter([
                'pageSize' => $args['limit'] ?? null,
            ])
        )['localPosts'] ?? []);
    }
}
