<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Database\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class DatabaseTableMetaType extends GenericType
{
    public const NAME = 'DatabaseTableMetadata';
    public const LABEL = 'Table';

    public function config(): array
    {
        return [
            'fields' => [
                'recordsCount' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Total Records',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }
}
