<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Dynamic\Listener;

use function YOOtheme\app;
use function YOOtheme\trans;
use YOOtheme\Str;
use YOOtheme\Builder\Source;
use YOOtheme\Builder\Wordpress\Source\Type\CustomPostQueryType;
use YOOtheme\Builder\Wordpress\Source\Type\CustomTaxonomyQueryType;
use YOOtheme\Builder\Wordpress\Source\Helper as SourceHelper;
use YOOtheme\GraphQL\Type\Definition\FieldDefinition;
use ZOOlanders\YOOessentials\Dynamic\SourceResolverManager;

class ExtendSourcesWordPress extends ExtendSources
{
    /**
     * @param Source $source
     */
    public function source($source)
    {
        if (app()->config->get('app.platform') !== 'wordpress' || !class_exists(SourceHelper::class)) {
            return;
        }

        $types = SourceResolverManager::getSourceTypeDefinitions($source) ?? [];

        if (class_exists(CustomPostQueryType::class)) {
            foreach (SourceHelper::getPostTypes() as $type) {
                $name = Str::camelCase($type->name, true);
                $base = Str::camelCase(SourceHelper::getBase($type), true);
                $typeName = Str::camelCase([$base, 'Query'], true);

                $taxonomies = SourceHelper::getObjectTaxonomies($type->name);
                $terms = $taxonomies ? ['source' => true] : [];

                if (isset($types[$typeName])) {
                    $source->objectType($typeName, [
                        'fields' => [
                            "custom{$name}" => [
                                'metadata' => [
                                    'fields' => array_merge(
                                        [
                                            'id' => [
                                                'source' => true
                                            ],
                                        ],
                                        $terms ? ['terms' => $terms] : [],
                                        [
                                            'users' => [
                                                'source' => true,
                                            ],
                                            'offset' => [
                                                'source' => true,
                                            ],
                                        ],
                                    ),
                                ],
                            ],
                            "custom{$base}" => [
                                'metadata' => [
                                    'fields' => array_merge(
                                        $terms ? ['terms' => $terms] : [],
                                        [
                                            'users' => [
                                                'source' => true,
                                            ],
                                            '_offset' => [
                                                'fields' => [
                                                    'offset' => [
                                                        'source' => true,
                                                    ],
                                                    'limit' => [
                                                        'source' => true,
                                                    ],
                                                ],
                                            ],
                                        ]
                                    )
                                ],
                            ],
                        ]
                    ]);
                }
            }
        }

        if (class_exists(CustomTaxonomyQueryType::class)) {
            foreach (SourceHelper::getTaxonomies() as $taxonomy) {
                $base = Str::camelCase(SourceHelper::getBase($taxonomy), true);
                $name = Str::camelCase($taxonomy->name, true);
                $typeName = Str::camelCase([$base, 'Query'], true);

                if (isset($types[$typeName])) {
                    $source->objectType($typeName, [
                        'fields' => [
                            "custom{$name}" => [
                                'metadata' => [
                                    'fields' => [
                                        'id' => [
                                            'source' => true
                                        ]
                                    ]
                                ]
                            ],
                            "custom{$base}" => [
                                'metadata' => [
                                    'fields' => ($taxonomy->hierarchical
                                        ? [
                                            'id' => [
                                                'source' => 'true',
                                            ],
                                        ]
                                        : []) + [
                                            '_offset' => [
                                                'fields' => [
                                                    'offset' => [
                                                        'source' => 'true',
                                                    ],
                                                    'limit' => [
                                                        'source' => 'true',
                                                    ],
                                                ],
                                            ],
                                        ],
                                ],
                            ],
                        ]
                    ]);
                }
            }
        }
    }

    public function metadata($metadata, $type)
    {
        if (app()->config->get('app.platform') !== 'wordpress') {
            return $metadata;
        }

        $this->extendWordpressSources($metadata, $type);

        return $metadata;
    }

    protected function extendWordpressSources(&$metadata, $type)
    {
        if (!($type instanceof FieldDefinition)) {
            return $metadata;
        }

        $group = $metadata['group'] ?? '';

        if ($group === trans('Page')) {
            if ($type->name === 'singlePost') {
                $metadata['description'] = 'The current post';
            }

            if ($type->name === 'singlePreviousPost') {
                $metadata['description'] = 'The previous post from the current feed';
            }

            if ($type->name === 'singleNextPost') {
                $metadata['description'] = 'The next post from the current feed';
            }

            if ($type->name === 'singlePage') {
                $metadata['description'] = 'The current page';
            }

            if ($type->name === 'singlePreviousPage') {
                $metadata['description'] = 'The previous page from the current feed';
            }

            if ($type->name === 'singleNextPage') {
                $metadata['description'] = 'The next page from the current feed';
            }

            if ($type->name === 'categoryPost') {
                $metadata['description'] = 'The current categories';
            }

            if ($type->name === 'taxonomyCategory') {
                $metadata['description'] = 'The current categories';
            }

            if ($type->name === 'categoryPostSingle') {
                $metadata['description'] = 'The current post category';
            }

            if ($type->name === 'taxonomyPostTag') {
                $metadata['description'] = 'The current post tags';
            }

            if ($type->name === 'postTagPost') {
                $metadata['description'] = 'The current post tags';
            }

            if ($type->name === 'postTagPostSingle') {
                $metadata['description'] = 'The current post tag';
            }

            if ($type->name === 'authorArchive') {
                $metadata['description'] = 'The current author archive';
            }
        }

        if ($group === trans('Custom')) {
            if ($type->name === 'posts') {
                $metadata['label'] = trans('Custom Posts');
                $metadata['group'] = trans('Custom');
                $metadata['description'] = 'Fetch posts by custom criteria';
            }

            if ($type->name === 'customPost') {
                $metadata['description'] = 'Fetch a single post';
                $this->setFieldSource($metadata, ['id', '_offset.fields.offset', '_offset.fields.limit']);
            }

            if ($type->name === 'customPosts') {
                $metadata['description'] = 'Fetch a list of posts';
                $this->setFieldSource($metadata, ['id', '_offset.fields.offset', '_offset.fields.limit']);
            }

            if ($type->name === 'pages') {
                $metadata['label'] = trans('Custom Pages');
                $metadata['group'] = trans('Custom');
                $metadata['description'] = 'Fetch pages by custom criteria';
            }

            if ($type->name === 'customPage') {
                $metadata['description'] = 'Fetch a single page';
                $this->setFieldSource($metadata, ['id', '_offset.fields.offset']);
            }

            if ($type->name === 'customPages') {
                $metadata['description'] = 'Fetch a list of pages';
                $this->setFieldSource($metadata, ['id', '_offset.fields.offset', '_offset.fields.limit']);
            }

            if ($type->name === 'categories') {
                $metadata['label'] = trans('Custom Categories');
                $metadata['group'] = trans('Custom');
                $metadata['description'] = 'Fetch categories by custom criteria';
            }

            if ($type->name === 'customCategory') {
                $metadata['description'] = 'Fetch a single category';
                $this->setFieldSource($metadata, ['id']);
            }

            if ($type->name === 'customCategories') {
                $metadata['description'] = 'Fetch a list of categories';
                $this->setFieldSource($metadata, ['id', '_offset.fields.offset', '_offset.fields.limit']);
            }

            if ($type->name === 'tags') {
                $metadata['label'] = trans('Custom Tags');
                $metadata['group'] = trans('Custom');
                $metadata['description'] = 'Fetch tags by custom criteria';
            }

            if ($type->name === 'customPostTag') {
                $metadata['description'] = 'Fetch a single tag';
                $this->setFieldSource($metadata, ['id']);
            }

            if ($type->name === 'customTags') {
                $metadata['description'] = 'Fetch a list of tags';
                $this->setFieldSource($metadata, ['parent_id', '_offset.fields.offset', '_offset.fields.limit']);
            }

            if ($type->name === 'customUser') {
                $metadata['description'] = 'Fetch a single user profile';
                $this->setFieldSource($metadata, ['id']);
            }

            if ($type->name === 'customUsers') {
                $metadata['description'] = 'Fetch a list of user profiles';
                $this->setFieldSource($metadata, ['roles', '_offset.fields.offset', '_offset.fields.limit']);
            }

            if ($type->name === 'customMenuItem') {
                $metadata['description'] = 'Fetch a single menu item';
                $this->setFieldSource($metadata, ['id', 'menu']);
            }

            if ($type->name === 'customMenuItems') {
                $metadata['description'] = 'Fetch a list of menu items';
                $this->setFieldSource($metadata, ['id', 'ids', 'parent']);
            }
        }
    }
}
