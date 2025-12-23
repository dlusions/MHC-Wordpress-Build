<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\AcyMailing\Type;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Api\AcyMailing\AcyMailingApiInterface;

class AcyMailingCustomListsQueryType
{
    public const NAME = 'customAcymailingLists';
    public const LABEL = 'Custom AcyMailing Lists';
    public const DESCRIPTION = 'Fetch AcyMailing subscriber lists';

    protected const DEFAULT_OFFSET = 0;
    protected const DEFAULT_LIMIT = 20;

    public static function config(): array
    {
        return [
            'fields' => [
                self::NAME => [
                    'type' => [
                        'listOf' => AcyMailingListType::NAME,
                    ],

                    'args' => [
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'limit' => [
                            'type' => 'Int',
                        ],
                    ],

                    'metadata' => [
                        'group' => 'Custom',
                        'label' => self::LABEL,
                        'description' => self::DESCRIPTION,
                        'fields' => [
                            '_offset_limit' => [
                                'type' => 'grid',
                                'width' => '1-2',
                                'description' => 'Set the starting point and limit the number of records.',
                                'fields' => [
                                    'offset' => [
                                        'label' => 'Start',
                                        'type' => 'yooessentials-number',
                                        'modifier' => 1,
                                        'default' => self::DEFAULT_OFFSET,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 0,
                                            'placeholder' => self::DEFAULT_OFFSET,
                                        ],
                                    ],
                                    'limit' => [
                                        'label' => 'Quantity',
                                        'type' => 'yooessentials-number',
                                        'default' => self::DEFAULT_LIMIT,
                                        'source' => true,
                                        'attrs' => [
                                            'min' => 1,
                                            'placeholder' => self::DEFAULT_LIMIT,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],

                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root, array $args): ?array
    {
        /** @var AcyMailingApiInterface $acyMailing */
        $acyMailing = app(AcyMailingApiInterface::class);

        $args['filters'] = [
            'active' => 1,
        ];

        return $acyMailing->getLists($args);
    }
}
