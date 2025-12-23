<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Instagram\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Provider\Instagram\InstagramSource;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\Instagram\HasApiRequest;

class InstagramUserQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData, HasApiRequest;

    public const NAME = 'user';
    public const LABEL = 'User';
    public const DESCRIPTION = 'User data';

    public static function getCacheKey(): string
    {
        return 'instagram-user';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => InstagramUserType::NAME,

                    'args' => [
                        'cache' => [
                            'type' => 'Int',
                        ],
                    ],

                    'metadata' => [
                        'group' => 'Essentials',
                        'label' => $this->label(),
                        'description' => self::DESCRIPTION,
                        'fields' => [
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

        if (!$source) {
            return [];
        }

        return self::resolveFromCache($source, $args, fn () => self::api($source->account())->user($source->pageId()));
    }
}
