<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleBusinessProfile\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class GoogleBusinessProfilePostOfferType extends GenericType
{
    public const NAME = 'GoogleBusinessProfilePostOffer';
    public const LABEL = 'Post Offer';

    public function config(): array
    {
        return [
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
            'fields' => [
                'couponCode' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Coupon Code',
                    ],
                ],
                'redeemOnlineUrl' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Redeem Online URL',
                    ],
                ],
                'termsConditions' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Terms & Conditions',
                        'filters' => ['limit'],
                    ],
                ],
            ],
        ];
    }
}
