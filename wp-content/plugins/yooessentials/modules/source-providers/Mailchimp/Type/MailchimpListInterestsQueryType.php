<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Mailchimp\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Provider\Mailchimp\MailchimpSource;
use ZOOlanders\YOOessentials\Form\Action\Mailchimp\HasApiRequest;

class MailchimpListInterestsQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData, HasApiRequest;

    private MailchimpListInterestType $interestType;

    public const NAME = 'listInterests';
    public const LABEL = 'List Interests';
    public const DESCRIPTION = 'Audience interests';

    public static function getCacheKey(): string
    {
        return 'mailchimp-list-interests-query';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => ['listOf' => MailchimpListInterestType::NAME],

                    'args' => [
                        'categoryId' => [
                            'type' => 'String',
                        ],
                        'offset' => [
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
                            'categoryId' => [
                                'label' => 'Category',
                                'type' => 'yooessentials-select-dropdown-async',
                                'description' => 'Category which interests to fetch.',
                                'endpoint' => 'yooessentials/source/mailchimp/interest-categories',
                                'meta' => false,
                                'params' => [
                                    'source_id' => $this->source->id(),
                                ],
                            ],
                            '_offset_limit' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'description' => 'Set the starting point and limit the number of interests.',
                                'fields' => [
                                    'offset' => [
                                        'label' => 'Start',
                                        'type' => 'yooessentials-number',
                                        'modifier' => 1,
                                        'default' => MailchimpSource::RECORDS_OFFSET,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 0,
                                            'placeholder' => MailchimpSource::RECORDS_OFFSET,
                                        ],
                                    ],
                                    'count' => [
                                        'label' => 'Quantity',
                                        'type' => 'yooessentials-number',
                                        'default' => MailchimpSource::RECORDS_LIMIT,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 1,
                                            'placeholder' => MailchimpSource::RECORDS_LIMIT,
                                        ],
                                    ],
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
        /** @var MailchimpSource */
        $source = self::loadSource($args, MailchimpSource::class);

        $categoryId = $args['categoryId'] ?? null;

        if (!$source || !$categoryId) {
            return [];
        }

        $result = self::resolveFromCache($source, $args, function () use ($source, $args) {
            /** @var MailchimpApiInterface */
            $api = self::api($source->auth);

            return $api->listInterestsInCategory($source->list, $args['categoryId'], $args);
        });

        return $result;
    }
}
