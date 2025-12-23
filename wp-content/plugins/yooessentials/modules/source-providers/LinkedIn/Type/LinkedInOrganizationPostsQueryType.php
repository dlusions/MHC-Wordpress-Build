<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\LinkedIn\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\LinkedIn\LinkedInSource;

class LinkedInOrganizationPostsQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'posts';
    public const LABEL = 'Posts';
    public const DESCRIPTION = 'List of posts belonging to an organization';

    public static function getCacheKey(): string
    {
        return 'linkedin-organization-posts';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => [
                        'listOf' => LinkedInPostType::NAME,
                    ],

                    'args' => [
                        'sortBy' => [
                            'type' => 'String',
                        ],
                        'start' => [
                            'type' => 'Int',
                        ],
                        'count' => [
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
                            '_pagination' => [
                                'description' => 'Set the start and the number of posts per page.',
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'start' => [
                                        'label' => 'Start',
                                        'type' => 'yooessentials-number',
                                        'default' => 0,
                                        'modifier' => 1,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 1,
                                            'placeholder' => 1,
                                        ],
                                    ],
                                    'count' => [
                                        'label' => 'Count',
                                        'type' => 'yooessentials-number',
                                        'default' => 10,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 1,
                                            'max' => 100,
                                            'placeholder' => 10,
                                        ],
                                    ],
                                ],
                            ],
                            'sortBy' => [
                                'label' => 'Sort By',
                                'type' => 'select',
                                'default' => 'LAST_MODIFIED',
                                'options' => [
                                    'Last Modified' => 'LAST_MODIFIED',
                                    'Created' => 'CREATED',
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

    public static function resolve($root, array $args)
    {
        /** @var LinkedInSource */
        $source = self::loadSource($args, LinkedInSource::class);

        $account = $source->config('account');
        $org = $source->config('organization');

        if (!$source || !$account || !$org) {
            return [];
        }

        $posts = self::resolveFromCache($source, $args, fn () => $source->api($account)->postsByAuthor($org, $args));

        return $posts;
    }
}
