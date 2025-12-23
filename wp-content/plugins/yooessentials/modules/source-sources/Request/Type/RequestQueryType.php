<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Request\Type;

use function YOOtheme\app;

class RequestQueryType
{
    public static function config(): array
    {
        return [
            'fields' => [
                'yooessentials_request' => [
                    'type' => 'YooessentialsRequest',
                    'metadata' => [
                        'label' => 'Request',
                        'group' => 'Global',
                        'description' => 'Current request data, including timestamp and URL Query',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],
            ],
        ];
    }

    public static function resolve()
    {
        return app()->config->get('req', []);
    }
}
