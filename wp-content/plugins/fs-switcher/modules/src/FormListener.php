<?php /**
 * @package     [FS] Switcher Pro for YOOtheme Pro
 * @subpackage  fs-switcher
 *
 * @author      Flart Studio https://flart.studio
 * @copyright   Copyright (C) 2026 Flart Studio. All rights reserved.
 * @license     GNU General Public License version 2 or later; see https://flart.studio/license
 * @link        https://flart.studio/yootheme-pro/switcher
 * @build       (FLART_BUILD_NUMBER)
 */

/** @noinspection DuplicatedCode */

namespace FlartStudio\YOOtheme\Switcher;

defined('_JEXEC') or defined('ABSPATH') or die();

class FormListener
{
    private const GRIDS_COUNT = 6;
    private const FIELDSETS_COUNT = 20;

    /**
     * Default "Inherit" option to prepend
     * @since 1.5.0
     */
    private const INHERIT_OPTION = [
        'Inherit' => '',
    ];

    /**
     * Configuration for fs_switcher and fs_switcher_sl element modifications
     * @since 1.5.0
     */
    private const EXTEND_ELEMENT = [
        'iterated' => [
            'grid_panels' => [
                'target' => 'panels',
                'pattern' => '_fs_switcher_pro_grid_{n}_settings',
                'placeholder' => '{g}',
                'count' => self::GRIDS_COUNT,
                'fields' => [
                    'grid_{g}_divider' => [
                        'enable' => "grid_{g}_column_gap != 'collapse' && grid_{g}_row_gap != 'collapse'",
                    ],
                    'grid_{g}_column_align' => [
                        'enable' => '!grid_{g}_slider',
                    ],
                    'grid_{g}_text_align_breakpoint' => [
                        'label' => 'Text Alignment Breakpoint',
                        'description' => 'Choose the screen width from which the text alignment takes effect.',
                        'default' => '',
                        'show' => 'grid_{g}_text_align',
                    ],
                    'grid_{g}_text_align_fallback' => [
                        'show' => 'grid_{g}_text_align && grid_{g}_text_align_breakpoint',
                    ],
                    'grid_{g}_default' => [
                        'label' => 'Phone Portrait',
                        'default' => '1-1',
                    ],
                    'grid_{g}_small' => [
                        'label' => 'Phone Landscape',
                        'prepend_options' => self::INHERIT_OPTION,
                    ],
                    'grid_{g}_medium' => [
                        'label' => 'Tablet Landscape',
                        'prepend_options' => self::INHERIT_OPTION,
                    ],
                    'grid_{g}_large' => [
                        'label' => 'Desktop',
                        'prepend_options' => self::INHERIT_OPTION,
                    ],
                    'grid_{g}_xlarge' => [
                        'label' => 'Large Screens',
                        'prepend_options' => self::INHERIT_OPTION,
                    ],
                    '_grid_{g}_slider_panel' => [
                        'show' => 'grid_{g}_slider',
                    ],
                    'grid_{g}_panel_card_offset' => [
                        'show' => 'grid_{g}_panel_style',
                    ],
                    'grid_{g}_image_grid_width' => [
                        'show' => "\$match(grid_{g}_image_align, 'left|right')",
                    ],
                    'grid_{g}_image_grid_column_gap' => [
                        'label' => 'Grid Column Gap',
                        'show' => "\$match(grid_{g}_image_align, 'left|right')",
                    ],
                    'grid_{g}_image_grid_row_gap' => [
                        'label' => 'Grid Row Gap',
                        'description' => 'Set the vertical gap when grid items stack.',
                        'show' => "\$match(grid_{g}_image_align, 'left|right')",
                    ],
                    'grid_{g}_image_grid_breakpoint' => [
                        'show' => "\$match(grid_{g}_image_align, 'left|right')",
                    ],
                    'grid_{g}_image_vertical_align' => [
                        'show' => "\$match(grid_{g}_image_align, 'left|right')",
                    ],
                    'grid_{g}_image_margin_bottom' => [
                        'show' => "grid_{g}_image_align == 'top'",
                    ],
                    'grid_{g}_image_margin_top' => [
                        'show' => "grid_{g}_image_align == 'bottom'",
                    ],
                    'grid_{g}_image_svg_animate' => [
                        'show' => 'grid_{g}_image_svg_inline',
                    ],
                    'grid_{g}_image_svg_color' => [
                        'show' => 'grid_{g}_image_svg_inline',
                    ],
                ],
            ],
            'fieldset_panels' => [
                'target' => 'panels',
                'pattern' => '_fs_switcher_pro_fieldset_{n}_settings',
                'placeholder' => '{f}',
                'count' => self::FIELDSETS_COUNT,
                'fields' => [
                    'text_{f}_limit_length' => [
                        'enable' => 'text_{f}_limit',
                    ],
                    'fieldset_{f}_mixed_width_default' => [
                        'label' => 'Phone Portrait',
                        'prepend_options' => self::INHERIT_OPTION,
                        'enable' => "enable_fieldsets_mixed_width",
                    ],
                    'fieldset_{f}_mixed_width_small' => [
                        'label' => 'Phone Landscape',
                        'prepend_options' => self::INHERIT_OPTION,
                        'enable' => "enable_fieldsets_mixed_width",
                    ],
                    'fieldset_{f}_mixed_width_medium' => [
                        'label' => 'Tablet Landscape',
                        'prepend_options' => self::INHERIT_OPTION,
                        'enable' => "enable_fieldsets_mixed_width",
                    ],
                    'fieldset_{f}_mixed_width_large' => [
                        'label' => 'Desktop',
                        'prepend_options' => self::INHERIT_OPTION,
                        'enable' => "enable_fieldsets_mixed_width",
                    ],
                    'fieldset_{f}_mixed_width_xlarge' => [
                        'label' => 'Large Screens',
                        'prepend_options' => self::INHERIT_OPTION,
                        'enable' => "enable_fieldsets_mixed_width",
                    ],
                ],
            ],
        ],
        'static' => [
            'switcher_grid_column_gap' => [
                'label' => 'Grid Column Gap',
                'show' => "switcher_style != 'accordion' && \$match(switcher_position, 'left|right')",
            ],
            'switcher_grid_row_gap' => [
                'label' => 'Grid Row Gap',
                'description' => 'Set the vertical gap when grid items stack.',
                'show' => "switcher_style != 'accordion' && \$match(switcher_position, 'left|right')",
            ],
            'position_sticky_breakpoint' => [
                'label' => 'Sticky Breakpoint',
                'description' => 'Make the column sticky only on screens this size or larger.',
                'show' => "position_sticky && switcher_style != 'accordion' && \$match(switcher_position, 'left|right')",
            ],
            'switcher_thumbnail_grid_column_gap' => [
                'label' => 'Grid Row Gap',
                'description' => 'Set the vertical gap when grid items stack.',
                'unset_options' => ['None'],
                'show' => "switcher_style == 'accordion'",
            ],
            'switcher_thumbnail_label_visibility' => [
                'label' => 'Label Visibility',
                'description' => 'Show or hide the label based on the device width. Hidden labels will also hide the associated columns, rows, and sections.',
                'show' => "switcher_style == 'thumbnav' && switcher_thumbnail_label"
            ],
            'switcher_thumbnail_svg_color' => [
                'enable' => "switcher_style == 'thumbnav' && switcher_thumbnail_svg_inline",
            ],
            'title_limit' => [
                'enable' => "(title_display != 'lightbox' && lightbox || !lightbox)",
            ],
            'title_limit_length' => [
                'enable' => "title_limit && (title_display != 'lightbox' && lightbox || !lightbox)",
            ],
            'title_style' => [
                'enable' => "(title_display != 'lightbox' && lightbox || !lightbox)",
            ],
            'title_decoration' => [
                'enable' => "(title_display != 'lightbox' && lightbox || !lightbox)",
            ],
            'title_color' => [
                'enable' => "(title_display != 'lightbox' && lightbox || !lightbox)",
            ],
            'title_grid_width' => [
                'enable' => "(title_display != 'lightbox' && lightbox || !lightbox)",
                'show' => "title_align == 'left'",
            ],
            'title_grid_column_gap' => [
                'label' => 'Grid Column Gap',
                'enable' => "(title_display != 'lightbox' && lightbox || !lightbox)",
                'show' => "title_align == 'left'",
            ],
            'title_grid_row_gap' => [
                'label' => 'Grid Row Gap',
                'description' => 'Set the vertical gap when grid items stack.',
                'enable' => "(title_display != 'lightbox' && lightbox || !lightbox)",
                'show' => "title_align == 'left'",
            ],
            'title_grid_breakpoint' => [
                'enable' => "(title_display != 'lightbox' && lightbox || !lightbox)",
                'show' => "title_align == 'left'",
            ],
            'title_element' => [
                'default' => 'h3',
                'enable' => "(title_display != 'lightbox' && lightbox || !lightbox)",
            ],
            'title_margin' => [
                'enable' => "(title_display != 'lightbox' && lightbox || !lightbox)",
            ],
            'meta_limit_length' => [
                'enable' => 'meta_limit',
            ],
            'content_limit' => [
                'enable' => "(content_display != 'lightbox' && lightbox || !lightbox)",
            ],
            'content_limit_length' => [
                'enable' => "content_limit && (content_display != 'lightbox' && lightbox || !lightbox)",
            ],
            'content_style' => [
                'enable' => "(content_display != 'lightbox' && lightbox || !lightbox)",
            ],
            'content_color' => [
                'enable' => "(content_display != 'lightbox' && lightbox || !lightbox)",
            ],
            'content_column_breakpoint' => [
                'label' => "Columns Breakpoint",
                'description' => "Set the device width from which the text columns should apply.",
                'enable' => "content_column && (content_display != 'lightbox' && lightbox || !lightbox)",
            ],
            'content_element' => [
                'enable' => "(content_display != 'lightbox' && lightbox || !lightbox)",
            ],
            'content_margin' => [
                'enable' => "(content_display != 'lightbox' && lightbox || !lightbox)",
            ],
            'content_visibility' => [
                'enable' => "(content_display != 'lightbox' && lightbox || !lightbox)",
            ],
            'sublayout_margin' => [
                'enable' => "\$match(sublayout_mode, 'native|mixed')",
            ],
            'image_grid_width' => [
                'show' => "\$match(image_align, 'left|right')",
            ],
            'image_grid_column_gap' => [
                'label' => 'Grid Column Gap',
                'show' => "\$match(image_align, 'left|right')",
            ],
            'image_grid_row_gap' => [
                'label' => 'Grid Row Gap',
                'description' => 'Set the vertical gap when grid items stack.',
                'show' => "\$match(image_align, 'left|right')",
            ],
            'image_grid_breakpoint' => [
                'show' => "\$match(image_align, 'left|right')",
            ],
            'image_svg_color' => [
                'show' => 'image_svg_inline',
            ],
            'image_margin' => [
                'show' => "(image_align == 'bottom' || show_image && switcher_style == 'accordion')",
            ],
            'lightbox_text_color' => [
                'enable' => 'lightbox',
            ],
        ]
    ];

    /**
     * Configuration for fs_switcher_item modifications
     * @since 1.5.0
     */
    private const EXTEND_ITEM = [
        'iterated' => [
            'grid_panels' => [
                'target' => 'panels',
                'pattern' => '_fs_switcher_pro_item_grid_{n}_settings',
                'placeholder' => '{g}',
                'count' => self::GRIDS_COUNT,
                'fields' => [
                    'grid_{g}_position' => [
                        'label' => 'Position',
                        'default' => '',
                        'prepend_options' => self::INHERIT_OPTION,
                    ],
                    'grid_{g}_panel_style' => [
                        'label' => 'Panel',
                        'unset_options' => ['None'],
                        'prepend_options' => self::INHERIT_OPTION,
                    ],
                    'grid_{g}_visibility' => [
                        'unset_options' => ['Always'],
                        'prepend_options' => self::INHERIT_OPTION,
                    ],
                ],
            ],
            'fieldset_panels' => [
                'target' => 'panels',
                'pattern' => '_fs_switcher_pro_item_fieldset_{n}_settings',
                'placeholder' => '{f}',
                'count' => self::FIELDSETS_COUNT,
                'fields' => [
                    'custom_image_{f}' => [
                        'enable' => "!custom_image_{f}_icon",
                    ],
                    'custom_image_{f}_alt' => [
                        'enable' => "custom_image_{f} && !custom_image_{f}_icon",
                    ],
                    'custom_image_{f}_icon' => [
                        'enable' => "!custom_image_{f}",
                    ],
                    'custom_link_{f}_target' => [
                        'enable' => "custom_link_{f} && !custom_link_{f}_toggle",
                    ],
                    'custom_link_{f}_toggle' => [
                        'enable' => "custom_link_{f}",
                    ],
                    'fieldset_{f}_target' => [
                        'description' => '',
                        'default' => '',
                        'prepend_options' => self::INHERIT_OPTION,
                    ],
                    'fieldset_{f}_visibility' => [
                        'description' => '',
                        'unset_options' => ['Always'],
                        'prepend_options' => self::INHERIT_OPTION,
                    ],
                    'fieldset_{f}_mixed_width_default' => [
                        'label' => 'Phone Portrait',
                        'prepend_options' => self::INHERIT_OPTION,
                        'enable' => "this.builder.parent(this.node)['props']['enable_fieldsets_mixed_width']",
                    ],
                    'fieldset_{f}_mixed_width_small' => [
                        'label' => 'Phone Landscape',
                        'prepend_options' => self::INHERIT_OPTION,
                        'enable' => "this.builder.parent(this.node)['props']['enable_fieldsets_mixed_width']",
                    ],
                    'fieldset_{f}_mixed_width_medium' => [
                        'label' => 'Tablet Landscape',
                        'prepend_options' => self::INHERIT_OPTION,
                        'enable' => "this.builder.parent(this.node)['props']['enable_fieldsets_mixed_width']",
                    ],
                    'fieldset_{f}_mixed_width_large' => [
                        'label' => 'Desktop',
                        'prepend_options' => self::INHERIT_OPTION,
                        'enable' => "this.builder.parent(this.node)['props']['enable_fieldsets_mixed_width']",
                    ],
                    'fieldset_{f}_mixed_width_xlarge' => [
                        'label' => 'Large Screens',
                        'prepend_options' => self::INHERIT_OPTION,
                        'enable' => "this.builder.parent(this.node)['props']['enable_fieldsets_mixed_width']",
                    ],
                ],
            ],
        ],
        'static' => [
            'item_element' => [
                'description' => 'Choose an HTML element or leave it neutral.',
            ]
        ]
    ];

    /**
     * Extend element options dynamically
     *
     * @param array $type The type to extend
     *
     * @return  array
     * @since   1.5.0
     */
    public static function extend(array $type): array
    {
        $elementName = $type['name'] ?? null;

        if (!$elementName) {
            return $type;
        }

        match ($elementName) {
            'fs_switcher', 'fs_switcher_sl' => self::transformElementType($type),
            'fs_switcher_item' => self::transformItemType($type),
            default => null,
        };

        return $type;
    }

    /**
     * Transform fs_switcher and fs_switcher_sl element type
     *
     * @param array  &$type The type array (passed by reference)
     *
     * @return  void
     * @since   1.5.0
     */
    private static function transformElementType(array &$type): void
    {
        self::applyExtensions($type, self::EXTEND_ELEMENT);
    }

    /**
     * Transform fs_switcher_item type
     *
     * @param array  &$type The type array (passed by reference)
     *
     * @return  void
     * @since   1.5.0
     */
    private static function transformItemType(array &$type): void
    {
        self::applyExtensions($type, self::EXTEND_ITEM);
    }

    /**
     * Apply all extensions (static and iterated) to the type
     *
     * @param array  &$type The type array (passed by reference)
     * @param array $extend The extension configuration
     *
     * @return  void
     * @since   1.5.0
     */
    private static function applyExtensions(array &$type, array $extend): void
    {
        // Apply iterated modifications
        if (isset($extend['iterated']) && is_array($extend['iterated'])) {
            foreach ($extend['iterated'] as $subconfig) {
                if (!isset($subconfig['target'], $subconfig['count'], $subconfig['placeholder'], $subconfig['fields']) ||
                    !is_array($subconfig['fields'])) {
                    continue;
                }

                $target = $subconfig['target'];
                $count = $subconfig['count'];
                $placeholder = $subconfig['placeholder'];
                $configFields = $subconfig['fields'];

                if ($target === 'panels') {
                    if (!isset($subconfig['pattern'], $type['panels']) || !is_array($type['panels'])) {
                        continue;
                    }
                    $pattern = $subconfig['pattern'];
                    for ($i = 1; $i <= $count; $i++) {
                        $panelName = str_replace('{n}', (string)$i, $pattern);
                        if (!isset($type['panels'][$panelName]['fields']) || !is_array($type['panels'][$panelName]['fields'])) {
                            continue;
                        }
                        self::applyIteratedModificationsToFields(
                            $type['panels'][$panelName]['fields'],
                            $configFields,
                            $placeholder,
                            (string)$i,
                        );
                    }
                } elseif ($target === 'fields') {
                    if (!isset($type['fields']) || !is_array($type['fields'])) {
                        continue;
                    }
                    for ($i = 1; $i <= $count; $i++) {
                        self::applyIteratedModificationsToFields(
                            $type['fields'],
                            $configFields,
                            $placeholder,
                            (string)$i,
                        );
                    }
                }
            }
        }

        // Apply static modifications to root fields
        if (isset($extend['static'], $type['fields']) && is_array($extend['static'])) {
            self::applyFieldModifications($type, $extend['static']);
        }
    }

    /**
     * Apply modifications to fields (non-iterated)
     *
     * @param array  &$type The type array (passed by reference)
     * @param array $extensions The field modifications to apply
     *
     * @return  void
     * @since   1.5.0
     */
    private static function applyFieldModifications(array &$type, array $extensions): void
    {
        if (empty($extensions) || !isset($type['fields']) || !is_array($type['fields'])) {
            return;
        }

        foreach ($extensions as $fieldKey => $modifications) {
            if (!isset($type['fields'][$fieldKey]) || !is_array($type['fields'][$fieldKey])) {
                continue;
            }

            self::applyModificationsToField(
                $type['fields'][$fieldKey],
                $modifications,
            );
        }
    }

    /**
     * Apply iterated modifications to a set of fields (e.g., panel fields or root fields)
     *
     * @param array   &$fields The field array (passed by reference)
     * @param array $config The configuration array
     * @param string $placeholder The placeholder to replace
     * @param string $number The number to replace placeholder with
     *
     * @return  void
     * @since   1.5.0
     */
    private static function applyIteratedModificationsToFields(
        array &$fields,
        array $config,
        string $placeholder,
        string $number,
    ): void {
        foreach ($config as $fieldPattern => $modifications) {
            $fieldKey = str_replace($placeholder, $number, $fieldPattern);

            if (!isset($fields[$fieldKey]) || !is_array($fields[$fieldKey])) {
                continue;
            }

            // Process modifications with placeholder replacement
            $processedModifications = self::processModifications($modifications, $placeholder, $number);

            self::applyModificationsToField(
                $fields[$fieldKey],
                $processedModifications,
            );
        }
    }

    /**
     * Process modifications by replacing placeholders
     *
     * @param array $modifications The modification array
     * @param string $placeholder The placeholder to replace
     * @param string $number The replacement value
     *
     * @return  array
     * @since   1.5.0
     */
    private static function processModifications(array $modifications, string $placeholder, string $number): array
    {
        $processed = [];

        foreach ($modifications as $key => $value) {
            if (is_string($value)) {
                $processed[$key] = str_replace($placeholder, $number, $value);
            } elseif ($key === 'unset_options' && is_array($value)) {
                $processed[$key] = array_map(
                    static fn(string $option): string => str_replace($placeholder, $number, $option),
                    $value,
                );
            } else {
                $processed[$key] = $value;
            }
        }

        return $processed;
    }

    /**
     * Apply modifications to a single field
     *
     * @param array  &$field The field array (passed by reference)
     * @param array $modifications The modifications to apply
     *
     * @return  void
     * @since   1.5.0
     */
    private static function applyModificationsToField(
        array &$field,
        array $modifications,
    ): void {
        foreach ($modifications as $property => $value) {
            match ($property) {
                'prepend_options' => self::prependOptions($field, $value),
                'append_options' => self::appendOptions($field, $value),
                'unset_options' => self::unsetOptions($field, $value),
                default => $field[$property] = $value,
            };
        }
    }

    /**
     * Prepend options to the field's option array
     *
     * @param array  &$field The field array (passed by reference)
     * @param array $options Options to prepend
     *
     * @return  void
     * @since   1.5.0
     */
    private static function prependOptions(array &$field, array $options): void
    {
        if (!isset($field['options']) || !is_array($field['options'])) {
            return;
        }

        $field['options'] = $options + $field['options'];
    }

    /**
     * Append options to the field's option array
     *
     * @param array  &$field The field array (passed by reference)
     * @param array $options Options to append
     *
     * @return  void
     * @since   1.5.0
     */
    private static function appendOptions(array &$field, array $options): void
    {
        if (!isset($field['options']) || !is_array($field['options'])) {
            return;
        }

        $field['options'] += $options;
    }

    /**
     * Remove options from the field's option array
     *
     * @param array  &$field The field array (passed by reference)
     * @param array $keys Keys to remove
     *
     * @return  void
     * @since   1.5.0
     */
    private static function unsetOptions(array &$field, array $keys): void
    {
        if (!isset($field['options']) || !is_array($field['options'])) {
            return;
        }

        foreach ($keys as $key) {
            unset($field['options'][$key]);
        }
    }
}