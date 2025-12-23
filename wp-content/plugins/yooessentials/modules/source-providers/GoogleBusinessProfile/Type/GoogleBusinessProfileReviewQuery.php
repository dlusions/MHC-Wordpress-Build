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
use ZOOlanders\YOOessentials\Source\Provider\GoogleBusinessProfile\GoogleBusinessProfileController;
use ZOOlanders\YOOessentials\Source\Provider\GoogleBusinessProfile\GoogleBusinessProfileSource;

class GoogleBusinessProfileReviewQuery extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'review';
    public const LABEL = 'Review';
    public const DESCRIPTION = 'A location review';

    public static function getCacheKey(): string
    {
        return 'google-business-profile-review';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => GoogleBusinessProfileReviewType::NAME,
                    'args' => [
                        'review_id' => [
                            'type' => 'String',
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
                            'review_id' => [
                                'label' => 'Review',
                                'type' => 'yooessentials-select-dropdown-async',
                                'description' => 'The location review which content to fetch.',
                                'endpoint' => GoogleBusinessProfileController::GET_REVIEWS_ENDPOINT,
                                'source' => true,
                                'params' => [
                                    'source_id' => $this->source->id(),
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

        $reviewId = $args['review_id'] ?? null;

        if (!$source || !$reviewId) {
            return [];
        }

        return self::resolveFromCache($source, $args, fn () => $source->api()->review($reviewId));
    }
}
