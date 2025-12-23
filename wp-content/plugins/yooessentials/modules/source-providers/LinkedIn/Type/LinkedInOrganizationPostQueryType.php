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

class LinkedInOrganizationPostQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData;

    public const NAME = 'post';
    public const LABEL = 'Post';
    public const DESCRIPTION = 'Single post belonging to an organization';

    public static function getCacheKey(): string
    {
        return 'linkedin-organization-post';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => LinkedInPostType::NAME,
                    'args' => [
                        'urn' => [
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
                            'urn' => [
                                'label' => 'URN',
                                'source' => true,
                                'description' => 'URN of the post, in full format like urn:li:ugcPost:1234567890',
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
        $post = $args['urn'] ?? null;

        if (!$source || !$account || !$post) {
            return [];
        }

        return self::resolveFromCache($source, $args, fn () => $source->api($account)->post($post));
    }
}
