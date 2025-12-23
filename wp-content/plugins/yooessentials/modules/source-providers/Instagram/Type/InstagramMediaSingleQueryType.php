<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Instagram\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\Instagram\InstagramSource;

class InstagramMediaSingleQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'singleMedia';
    public const LABEL = 'Single Media';
    public const DESCRIPTION = 'A single media';

    public static function getCacheKey(): string
    {
        return 'instagram-single-media';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => InstagramMediaType::NAME,

                    'args' => [
                        'id' => [
                            'type' => 'String',
                        ],
                        'cache' => [
                            'type' => 'Int',
                        ],
                    ],

                    'metadata' => [
                        'group' => 'Essentials',
                        'label' => $this->label(),
                        'description' => self::DESCRIPTION,
                        'fields' => [
                            'id' => [
                                'label' => 'ID',
                                'description' => 'The Media ID.',
                                'source' => true,
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
        /** @var InstagramSource */
        $source = self::loadSource($args, InstagramSource::class);

        $id = $args['id'] ?? null;

        if (!$source || !$id) {
            return [];
        }

        return self::resolveFromCache($source, $args, function () use ($source, $args) {
            $media = $source->api()->media($args['id']) ?? null;

            if (!$media) {
                return [];
            }

            $media['children'] = $source->api()->children($args['id']);

            return $media;
        });
    }
}
