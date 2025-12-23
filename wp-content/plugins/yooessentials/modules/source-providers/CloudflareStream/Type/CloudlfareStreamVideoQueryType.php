<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\CloudflareStream\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\CloudflareStream\CloudflareStreamSource;

class CloudlfareStreamVideoQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'video';
    public const LABEL = 'Video';
    public const DESCRIPTION = 'Single video';

    public static function getCacheKey(): string
    {
        return 'cloudflare-stream-video';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => CloudflareStreamVideoType::NAME,

                    'args' => [
                        'uid' => [
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
                            'uid' => [
                                'label' => 'Video ID',
                                'description' => 'Select the video ID to create the source from, or set it as dynamic.',
                                'source' => true,
                                'type' => 'yooessentials-cloudflare-stream',
                                'sourceId' => $this->source()->id(),
                            ],

                            'cache' => $this->cacheField(),
                        ],
                    ],

                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolve',
                            'args' => [
                                'source_id' => $this->source()->id(),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root, array $args)
    {
        /** @var CloudflareStreamSource */
        $source = self::loadSource($args, CloudflareStreamSource::class);

        $uid = $args['uid'] ?? null;

        if (!$source || !$uid) {
            return [];
        }

        $stream = self::resolveFromCache($source, $args, fn () => $source->api()->stream($args['uid']));

        $source->signStream($stream);

        return $stream;
    }
}
