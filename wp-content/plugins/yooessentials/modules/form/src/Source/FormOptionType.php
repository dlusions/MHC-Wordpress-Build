<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Source;

class FormOptionType
{
    public const NAME = 'YooessentialsFormOption';
    public const LABEL = 'Option';

    public static function config(): array
    {
        return [
            'fields' => [
                'value' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Value',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => self::LABEL
            ],
        ];
    }
}
