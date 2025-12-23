<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Source;

class FormQueryType
{
    public static function config(): array
    {
        return [
            'fields' => [
                'yooessentials_form_query' => [
                    'type' => FormType::NAME,
                    'metadata' => [
                        'group' => 'Submission',
                        'label' => FormType::LABEL,
                        'description' => 'Form submission source',
                    ],
                ],
            ],
        ];
    }
}
