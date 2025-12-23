<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Database\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\AbstractInputType;

class DatabaseFilterType extends AbstractInputType
{
    public const NAME = 'DatabaseFilter';
    public const LABEL = 'Database Abstract Filter';

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
                'relation' => [
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
