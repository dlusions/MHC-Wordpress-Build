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

class AcyMailingCustomSubscriberQueryType
{
    public const NAME = 'customAcymailingSubscriber';
    public const LABEL = 'Custom AcyMailing Subscriber';
    public const DESCRIPTION = 'Fetch AcyMailing subscriber';

    public static function config(): array
    {
        return [
            'fields' => [
                self::NAME => [
                    'type' => AcyMailingSubscriberType::NAME,

                    'args' => [
                        'email' => [
                            'type' => 'String',
                        ],
                    ],

                    'metadata' => [
                        'group' => 'Custom',
                        'label' => self::LABEL,
                        'description' => self::DESCRIPTION,
                        'fields' => [
                            'email' => [
                                'label' => 'Email',
                                'description' => 'The Subscribers\' email.',
                                'source' => true,
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

        $email = $args['email'] ?? 0;

        if ($email) {
            return (array) $acyMailing->getSubscriberByEmail($email);
        }

        return null;
    }
}
