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

class GoogleBusinessProfileReviewsQuery extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'reviews';
    public const LABEL = 'Reviews';
    public const DESCRIPTION = 'List of the location reviews';

    public static function getCacheKey(): string
    {
        return 'google-business-profile-reviews';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => ['listOf' => GoogleBusinessProfileReviewType::NAME],

                    'args' => [
                        'cache' => [
                            'type' => 'Int',
                        ],
                        'order_by' => [
                            'type' => 'String',
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
                            '_offset' => [
                                'description' => 'Set the order and limit the number of reviews.',
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'order_by' => [
                                        'label' => 'Order By',
                                        'type' => 'select',
                                        'default' => 'update_time desc',
                                        'options' => [
                                            'Latest' => 'update_time desc',
                                            'Rating Ascending' => 'rating',
                                            'Rating Descending' => 'rating desc',
                                        ],
                                    ],
                                    'limit' => [
                                        'label' => 'Quantity',
                                        'type' => 'limit',
                                        'default' => 10,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 1,
                                        ],
                                    ],
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

        return self::resolveFromCache($source, $args, fn () => $source->api()->reviews(
            $source->businessAccount,
            $source->location,
            array_filter([
                'pageSize' => $args['limit'] ?? null,
                'orderBy' => $args['order_by'] ?? null,
            ])
        )['reviews'] ?? []);
    }
}
