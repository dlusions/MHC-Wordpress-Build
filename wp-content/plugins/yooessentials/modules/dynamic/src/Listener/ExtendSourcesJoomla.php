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
use YOOtheme\GraphQL\Type\Definition\FieldDefinition;

class ExtendSourcesJoomla extends ExtendSources
{
    /**
     * @param Source $source
     */
    public function source($source)
    {
        if (app()->config->get('app.platform') !== 'joomla') {
            return;
        }

        $source->objectType('Article', [
            'fields' => [
                'tags' => [
                    'metadata' => [
                        'arguments' => [
                            'parent_id' => [
                                'source' => 'true',
                            ],
                        ]
                    ]
                ],
            ]
        ]);
    }

    public function metadata($metadata, $type)
    {
        if (app()->config->get('app.platform') !== 'joomla') {
            return $metadata;
        }

        $this->extendJoomlaQueries($metadata, $type);
        $this->extendZooQueries($metadata, $type);

        return $metadata;
    }

    protected function extendJoomlaQueries(&$metadata, $type)
    {
        if (!($type instanceof FieldDefinition)) {
            return $metadata;
        }

        $group = $metadata['group'] ?? '';

        if ($group === trans('Page')) {
            if ($type->name === 'article') {
                $metadata['description'] = 'The current article, category and author';
            }

            if ($type->name === 'prevArticle') {
                $metadata['description'] = 'The previous article from the current feed';
            }

            if ($type->name === 'nextArticle') {
                $metadata['description'] = 'The next article from the current feed';
            }
        }

        if ($group === trans('Custom')) {
            if ($type->name === 'customArticle') {
                $metadata['description'] = 'Fetch a single article';
                $this->setFieldSource($metadata, ['id', 'catid', 'tags', 'users', 'featured', 'offset']);
            }

            if ($type->name === 'customArticles') {
                $metadata['description'] = 'Fetch a list of articles';
                $this->setFieldSource($metadata, ['catid', 'tags', 'users', 'featured', '_offset.fields.offset', '_offset.fields.limit']);
            }

            if ($type->name === 'customCategory') {
                $metadata['description'] = 'Fetch a single category';
                $this->setFieldSource($metadata, ['id']);
            }

            if ($type->name === 'customCategories') {
                $metadata['description'] = 'Fetch a list of categories';
                $this->setFieldSource($metadata, ['catid', '_offset.fields.offset', '_offset.fields.limit']);
            }

            if ($type->name === 'customTag') {
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
                $this->setFieldSource($metadata, ['groups', '_offset.fields.offset', '_offset.fields.limit']);
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

    protected function extendZooQueries(&$metadata, $type)
    {
        if (!($type instanceof FieldDefinition)) {
            return $metadata;
        }

        $group = $metadata['group'] ?? '';

        if (Str::startsWith($type->name, 'zoo') && !isset($metadata['label'])) {
            $metadata['group'] = 'ZOO';
            $metadata['label'] = Str::titleCase(Str::snakeCase(str_replace('zoo', '', $type->name), ' '));
            $metadata['description'] = sprintf('Sources based on the ZOO %s app', $metadata['label']);
        }

        if ($group === trans('ZOO')) {
            if (Str::startsWith($type->name, 'custom')) {
                $metadata['label'] = str_replace('custom', '', $type->name);

                $metadata['description'] = Str::endsWith($metadata['label'], 's')
                    ? 'Fetch a list of '
                    : 'Fetch a single ';

                $metadata['description'] .= Str::titleCase($metadata['label']);
            }
        }
    }
}
