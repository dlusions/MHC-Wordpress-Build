<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Facebook\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class FacebookUserType extends GenericType
{
    public const NAME = 'FacebookUser';
    public const LABEL = 'User';

    public function config(): array
    {
        return [
            'fields' => [
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Full Name',
                        'filters' => ['limit'],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::fullName',
                    ],
                ],
                'short_name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Short Name',
                        'filters' => ['limit'],
                    ],
                ],
                'first_name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'First Name',
                        'filters' => ['limit'],
                    ],
                ],
                'last_name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Last Name',
                        'filters' => ['limit'],
                    ],
                ],
                'middle_name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Middle Name',
                        'filters' => ['limit'],
                    ],
                ],
                'picture' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Picture',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::picture',
                    ],
                ],
                'picture_height' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Picture Height',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::pictureHeight',
                    ],
                ],
                'picture_width' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Picture Width',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::pictureWidth',
                    ],
                ],
                'id' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID'
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function fullName($user): string
    {
        $name = $user['name_format'] ?? '{first} {last}';

        $name = str_replace('{first}', $user['first_name'] ?? '', $name);
        $name = str_replace('{middle}', $user['middle_name'] ?? '', $name);
        $name = str_replace('{last}', $user['last_name'] ?? '', $name);

        return trim($name) ?: $user['name'] ?? '';
    }

    public static function picture($user): string
    {
        $id = $user['id'] ?? '';
        $url = $user['picture']['data']['url'] ?? '';

        return Util\File::cacheMedia($url, "facebook-user-$id");
    }

    public static function pictureHeight($user): int
    {
        return $user['picture']['data']['height'] ?? '';
    }

    public static function pictureWidth($user): int
    {
        return $user['picture']['data']['width'] ?? '';
    }
}
