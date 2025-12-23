<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractInputType;

abstract class InMemoryFilterType extends AbstractInputType
{
    public function config(): array
    {
        return [
            'fields' => [
                'name' => [
                    'type' => 'String',
                ],
                'status' => [
                    'type' => 'String',
                ],
                'field' => [
                    'type' => 'String',
                ],
                'operator' => [
                    'type' => 'String',
                ],
                'value' => [
                    'type' => 'String',
                ],
            ],
        ];
    }
}
