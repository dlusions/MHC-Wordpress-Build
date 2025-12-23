<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Facebook\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\Facebook\FacebookSource;
use ZOOlanders\YOOessentials\Source\Provider\Facebook\HasApiRequest;
use ZOOlanders\YOOessentials\Api\Facebook\FacebookApiInterface as FacebookApi;

class FacebookPageEventsQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData, HasApiRequest;

    public const NAME = 'pageEvents';
    public const LABEL = 'Page Events';
    public const DESCRIPTION = 'List of page events';

    public static function getCacheKey(): string
    {
        return 'facebook-page-events';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => [
                        'listOf' => FacebookEventType::NAME,
                    ],

                    'args' => [
                        'since' => [
                            'type' => 'String',
                        ],
                        'until' => [
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
                            '_time_range' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'description' => 'Set the event\'s start and/or end time in ISO 8601 format to filter by, e.g. <code>2011-06-03</code>.',
                                'fields' => [
                                    'since' => [
                                        'label' => 'Since',
                                        'type' => 'yooessentials-datetime',
                                        'small' => true,
                                        'source' => true,
                                    ],
                                    'until' => [
                                        'label' => 'Until',
                                        'type' => 'yooessentials-datetime',
                                        'small' => true,
                                        'source' => true,
                                    ],
                                ],
                            ],
                            'limit' => [
                                'label' => 'Limit',
                                'type' => 'yooessentials-number',
                                'description' => 'The maximum amount of events to fetch.',
                                'default' => FacebookSource::MEDIA_LIMIT_DEFAULT,
                                'source' => true,
                                'attrs' => [
                                    'placeholder' => FacebookSource::MEDIA_LIMIT_DEFAULT,
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

        /** @var ?FacebookApi */
        $api = self::api($source->account());

        if (!$api) {
            return [];
        }

        return self::resolveFromCache($source, $args, fn () => $api->events($source->pageId(), $args));
    }
}
