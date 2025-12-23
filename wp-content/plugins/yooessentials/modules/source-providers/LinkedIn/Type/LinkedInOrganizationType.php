<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\LinkedIn\Type;

use YOOtheme\Url;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class LinkedInOrganizationType extends GenericType
{
    public const NAME = 'LinkedinOrganization';
    public const LABEL = 'Organization';

    public function config(): array
    {
        return [
            'fields' => [
                'localizedName' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Name',
                    ],
                ],
                'vanityName' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Vanity Name',
                    ],
                ],
                'localizedDescription' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Description',
                        'filters' => ['limit'],
                    ],
                ],
                'localizedWebsite' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Website',
                    ],
                ],
                'logo' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Logo URL',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveLogo',
                    ],
                ],
                'id' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                        'description' => 'Unique identifier of the organization',
                    ],
                ],
                // 'website' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Website',
                //     ],
                // ],
                // 'created' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Created',
                //     ],
                // ],
                // 'groups' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Groups',
                //     ],
                // ],
                // 'description' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Description',
                //     ],
                // ],
                // 'versionTag' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Version Tag',
                //     ],
                // ],
                // 'defaultLocale' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Default Locale',
                //     ],
                // ],
                // 'organizationType' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Organization Type',
                //     ],
                // ],
                // 'alternativeNames' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Alternative Names',
                //     ],
                // ],
                // 'specialties' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Specialties',
                //     ],
                // ],
                // 'localizedSpecialties' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Localized Specialties',
                //     ],
                // ],
                // 'primaryOrganizationType' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Primary Organization Type',
                //     ],
                // ],
                // 'locations' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Locations',
                //     ],
                // ],
                // 'lastModified' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Last Modified',
                //     ],
                // ],
                // 'autoCreated' => [
                //     'type' => 'Boolean',
                //     'metadata' => [
                //         'label' => 'Auto Created',
                //     ],
                // ],

            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function resolveLogo($org): string
    {
        $id = $org['id'] ?? '';
        $url = $org['logoV2']['original']['downloadUrl'] ?? '';

        if (Url::isValid($url)) {
            return Util\File::cacheMedia($url, "linkedin-organization-$id");
        }

        return '';
    }
}
