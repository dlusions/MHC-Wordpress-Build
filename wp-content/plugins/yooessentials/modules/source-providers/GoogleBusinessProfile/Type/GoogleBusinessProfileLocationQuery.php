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

class GoogleBusinessProfileLocationQuery extends AbstractQueryType
{
    use CachesResolvedSourceData, LoadsSourceFromArgs;

    public const NAME = 'location';
    public const LABEL = 'Location';
    public const DESCRIPTION = 'Location general data';

    public static function getCacheKey(): string
    {
        return 'google-business-profile-location';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => GoogleBusinessProfileLocationType::NAME,

                    'args' => [
                        'cache' => [
                            'type' => 'Int',
                        ],
                    ],

                    'metadata' => [
                        'group' => 'Essentials',
                        'label' => $this->label(),
                        'description' => $this->description(),
                        'fields' => [
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

        return self::resolveFromCache($source, $args, function () use ($source) {
            $location = $source->api()->location($source->location);
            $reviews = $source->api()->reviews($source->businessAccount, $source->location);

            $location['totalReviewCount'] = $reviews['totalReviewCount'] ?? 0;
            $location['averageRating'] = $reviews['averageRating'] ?? 0;

            return $location;
        });
    }
}
