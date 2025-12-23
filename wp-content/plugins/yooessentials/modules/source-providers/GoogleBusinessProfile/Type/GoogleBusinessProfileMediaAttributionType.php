<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GoogleBusinessProfile\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class GoogleBusinessProfileMediaAttributionType extends GenericType
{
    public const NAME = 'GoogleBusinessProfileMediaAttribution';
    public const LABEL = 'Media Attribution';

    public function config(): array
    {
        return [
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
            'fields' => [
                'profileName' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Profile Name',
                    ],
                ],
                'profileUrl' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Profile URL',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveProfileUrl',
                    ],
                ],
                'profilePhotoUrl' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Profile Photo URL',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveProfilePhotoUrl',
                    ],
                ],
            ],
        ];
    }

    public static function resolveProfilePhotoUrl($data): ?string
    {
        $url = $data['profilePhotoUrl'] ?? '';
        $cacheKey = 'gbp-media-attribution-profile-photo-' . sha1($url);

        return Util\File::cacheMedia($url, $cacheKey);
    }

    public static function resolveProfileUrl($data): ?string
    {
        $url = $data['profileUrl'] ?? '';
        $cacheKey = 'gbp-media-attribution-profile-' . sha1($url);

        return Util\File::cacheMedia($url, $cacheKey);
    }
}
