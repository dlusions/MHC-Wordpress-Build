<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Facebook\Type;

use ZOOlanders\YOOessentials\Api\Facebook\FacebookApiInterface;
use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\Facebook\FacebookSource;
use ZOOlanders\YOOessentials\Source\Provider\Facebook\HasApiRequest;

class FacebookPageQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData, HasApiRequest;

    public const NAME = 'page';
    public const LABEL = 'Page';
    public const DESCRIPTION = 'Page related data';

    public static function getCacheKey(): string
    {
        return 'facebook-page';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => FacebookPageType::NAME,

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

        return self::resolveFromCache($source, $args, fn () => $api->page($source->pageId()));
    }
}
