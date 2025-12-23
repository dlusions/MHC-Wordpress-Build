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

class GoogleBusinessProfileReviewerType extends GenericType
{
    public const NAME = 'GoogleBusinessProfileReviewer';
    public const LABEL = 'Reviewer';

    public function config(): array
    {
        return [
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
            'fields' => [
                'displayName' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Display Name',
                    ],
                ],
                'profilePhotoUrl' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Profile Photo URL',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveProfileUrl',
                    ],
                ],
                'isAnonymous' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => 'Is Anonymous',
                    ],
                ],
            ],
        ];
    }

    public static function resolveProfileUrl(array $reviewer): string
    {
        $url = $reviewer['profilePhotoUrl'] ?? '';
        $cacheKey = 'gbp-reviewer-photo-' . sha1($url);

        return Util\File::cacheMedia($url, $cacheKey);
    }
}
