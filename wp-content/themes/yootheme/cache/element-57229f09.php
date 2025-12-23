<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/fs-switcher/modules/element/switcher/sl/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'fs_switcher_sl', 
  'title' => 'Switcher SL', 
  'group' => 'Flart Studio', 
  'icon' => $filter->apply('url', 'images/icon.svg', $file), 
  'iconSmall' => $filter->apply('url', 'images/iconSmall.svg', $file), 
  'element' => true, 
  'container' => true, 
  'width' => 500, 
  'defaults' => [
    'switcher_animation' => 'fade', 
    'switcher_height' => true, 
    'switcher_swipe_disable' => true, 
    'switcher_autoplay_pause' => true, 
    'switcher_autoplay_progress_position' => 'item-top', 
    'switcher_autoplay_progress_fill' => '#333', 
    'switcher_autoplay_progress_track' => '#eee', 
    'switcher_style' => 'tab', 
    'switcher_position' => 'top', 
    'switcher_align' => 'left', 
    'switcher_grid_width' => 'auto', 
    'switcher_grid_breakpoint' => 'm', 
    'switcher_accordion_collapsible' => true, 
    'switcher_accordion_padding' => 'remove', 
    'switcher_thumbnail_grid_column_gap' => 'medium', 
    'switcher_thumbnail_svg_color' => 'emphasis', 
    'margin_top' => 'default', 
    'margin_bottom' => 'default'
  ], 
  'placeholder' => [
    'children' => [[
        'type' => 'fs_switcher_sl_item', 
        'props' => []
      ], [
        'type' => 'fs_switcher_sl_item', 
        'props' => []
      ], [
        'type' => 'fs_switcher_sl_item', 
        'props' => []
      ]]
  ], 
  'templates' => [
    'render' => $filter->apply('path', './../templates/template.php', $file), 
    'content' => $filter->apply('path', './../templates/content.php', $file)
  ], 
  'fields' => [
    'content' => [
      'label' => 'Items', 
      'type' => 'content-items', 
      'item' => 'fs_switcher_sl_item', 
      'media' => [
        'type' => 'image', 
        'item' => [
          'title' => 'title', 
          'image' => 'src'
        ]
      ]
    ], 
    'switcher_animation' => [
      'label' => 'Animation', 
      'description' => 'Select an animation that will be applied to the content items when toggling between them.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Fade' => 'fade', 
        'Scale Up' => 'scale-up', 
        'Scale Down' => 'scale-down', 
        'Slide Top Small' => 'slide-top-small', 
        'Slide Bottom Small' => 'slide-bottom-small', 
        'Slide Left Small' => 'slide-left-small', 
        'Slide Right Small' => 'slide-right-small', 
        'Slide Top Medium' => 'slide-top-medium', 
        'Slide Bottom Medium' => 'slide-bottom-medium', 
        'Slide Left Medium' => 'slide-left-medium', 
        'Slide Right Medium' => 'slide-right-medium', 
        'Slide Top 100%' => 'slide-top', 
        'Slide Bottom 100%' => 'slide-bottom', 
        'Slide Left 100%' => 'slide-left', 
        'Slide Right 100%' => 'slide-right'
      ], 
      'enable' => 'switcher_style != \'accordion\''
    ], 
    'switcher_height' => [
      'label' => 'Match Height', 
      'description' => 'Match the height of all content items.', 
      'type' => 'checkbox', 
      'text' => 'Match content height', 
      'enable' => 'switcher_style != \'accordion\''
    ], 
    'switcher_swipe_disable' => [
      'label' => 'Swipe', 
      'description' => 'Disable swipe gestures on mobile devices.', 
      'type' => 'checkbox', 
      'text' => 'Disable swipe navigation', 
      'enable' => 'switcher_style != \'accordion\''
    ], 
    'switcher_scroll' => [
      'label' => 'Scroll', 
      'description' => 'Scroll to the top of the switcher when changing tabs.', 
      'type' => 'checkbox', 
      'text' => 'Enable scroll on tab change'
    ], 
    'switcher_scroll_offset' => [
      'label' => 'Offset', 
      'description' => 'Distance in pixels from the top of the viewport where the switcher will be positioned after scrolling.', 
      'type' => 'range', 
      'attrs' => [
        'min' => 50, 
        'max' => 300, 
        'step' => 5, 
        'placeholder' => '100'
      ], 
      'show' => 'switcher_scroll'
    ], 
    'switcher_autoplay' => [
      'label' => 'Autoplay', 
      'description' => 'Automatically switch tabs at the set interval.', 
      'type' => 'checkbox', 
      'text' => 'Enable switcher autoplay'
    ], 
    '_fs_switcher_pro_autoplay' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_autoplay_settings', 
      'text' => 'Edit Settings', 
      'show' => 'switcher_autoplay'
    ], 
    'switcher_style' => [
      'label' => 'Type', 
      'description' => 'Select the navigation style. \'Pill\' and \'Divider\' styles are only available for horizontal Subnavs.', 
      'type' => 'select', 
      'options' => [
        'Tabs' => 'tab', 
        'Subnav Pill (Nav)' => 'subnav-pill', 
        'Subnav Divider (Nav)' => 'subnav-divider', 
        'Subnav (Nav)' => 'subnav', 
        'Thumbnav' => 'thumbnav', 
        'Accordion' => 'accordion'
      ]
    ], 
    'switcher_mode' => [
      'label' => 'Mode', 
      'description' => 'Choose how tabs are switched: \'Hover\' changes tabs on mouse-over, \'Click\' requires a mouse click.', 
      'type' => 'select', 
      'options' => [
        'Click' => '', 
        'Hover' => 'hover'
      ], 
      'show' => 'switcher_style != \'accordion\''
    ], 
    'switcher_mode_hover_trigger_click' => [
      'label' => 'Click Action', 
      'description' => 'In hover mode, clicking a navigation tab also follows the switcher item link, allowing users to open the linked page without interacting with the content area.', 
      'type' => 'checkbox', 
      'text' => 'Trigger item link on click', 
      'show' => 'switcher_style != \'accordion\' && switcher_mode == \'hover\''
    ], 
    'switcher_nowrap' => [
      'label' => 'Appearance', 
      'type' => 'checkbox', 
      'text' => 'Don\'t wrap into multiple lines', 
      'show' => 'switcher_style != \'accordion\''
    ], 
    'switcher_vertical_align' => [
      'type' => 'checkbox', 
      'text' => 'Center tab content vertically', 
      'show' => 'switcher_style != \'accordion\' && $match(switcher_position, \'left|right\')'
    ], 
    'switcher_style_primary' => [
      'type' => 'checkbox', 
      'text' => 'Primary navigation', 
      'show' => 'switcher_style != \'accordion\' && $match(switcher_style, \'(^subnav)\') && $match(switcher_position, \'left|right\')'
    ], 
    'switcher_grid_divider' => [
      'type' => 'checkbox', 
      'text' => 'Show grid divider', 
      'show' => 'switcher_style != \'accordion\' && $match(switcher_position, \'left|right\')'
    ], 
    'switcher_position' => [
      'label' => 'Position', 
      'description' => 'Set the navigation position: top, bottom, left, or right. Larger styles can be applied to left and right navigations.', 
      'type' => 'select', 
      'options' => [
        'Top' => 'top', 
        'Bottom' => 'bottom', 
        'Left' => 'left', 
        'Right' => 'right'
      ], 
      'show' => 'switcher_style != \'accordion\''
    ], 
    'switcher_grid_width' => [
      'label' => 'Grid Width', 
      'description' => 'Define the width of the navigation. Options include percent, fixed, or content-based width.', 
      'type' => 'select', 
      'options' => [
        'Auto' => 'auto', 
        '50%' => '1-2', 
        '33%' => '1-3', 
        '25%' => '1-4', 
        '20%' => '1-5', 
        'Small' => 'small', 
        'Medium' => 'medium', 
        'Large' => 'large'
      ], 
      'show' => 'switcher_style != \'accordion\' && $match(switcher_position, \'left|right\')'
    ], 
    'switcher_grid_column_gap' => $config->get('fs_switcher.grid_column_gap'), 
    'switcher_grid_row_gap' => $config->get('fs_switcher.grid_row_gap'), 
    'switcher_grid_breakpoint' => [
      'label' => 'Grid Breakpoint', 
      'description' => 'Choose the screen width at which grid items start stacking.', 
      'type' => 'select', 
      'options' => [
        'Small (Phone Landscape)' => 's', 
        'Medium (Tablet Landscape)' => 'm', 
        'Large (Desktop)' => 'l', 
        'X-Large (Large Screens)' => 'xl'
      ], 
      'show' => 'switcher_style != \'accordion\' && $match(switcher_position, \'left|right\')'
    ], 
    'switcher_align' => [
      'label' => 'Alignment', 
      'description' => 'Align the navigation items horizontally.', 
      'type' => 'select', 
      'options' => [
        'Left' => 'left', 
        'Right' => 'right', 
        'Center' => 'center', 
        'Justify' => 'justify'
      ], 
      'show' => 'switcher_style != \'accordion\''
    ], 
    'position_sticky' => [
      'label' => 'Sticky', 
      'description' => 'Keep the navigation column fixed at the top of the viewport while scrolling. It stops at the bottom of its container.', 
      'type' => 'checkbox', 
      'text' => 'Stick navigation to viewport', 
      'show' => 'switcher_style != \'accordion\' && $match(switcher_position, \'left|right\')'
    ], 
    'position_sticky_offset' => [
      'label' => 'Sticky Offset', 
      'description' => 'Set the offset from the top of the viewport before the column becomes sticky.', 
      'type' => 'number', 
      'attrs' => [
        'placeholder' => '0'
      ], 
      'show' => 'position_sticky && switcher_style != \'accordion\' && $match(switcher_position, \'left|right\')'
    ], 
    'position_sticky_breakpoint' => $config->get('fs_switcher.grid_breakpoint'), 
    'switcher_margin' => [
      'label' => 'Margin', 
      'description' => 'Set the vertical margin around the switcher navigation.', 
      'type' => 'select', 
      'options' => [
        'Small' => 'small', 
        'Default' => '', 
        'Medium' => 'medium', 
        'Large' => 'large', 
        'X-Large' => 'xlarge'
      ], 
      'show' => 'switcher_style != \'accordion\' && $match(switcher_position, \'top|bottom\')'
    ], 
    'switcher_accordion_padding' => [
      'label' => 'Content Padding', 
      'description' => 'Set padding for accordion content.', 
      'type' => 'select', 
      'options' => [
        'Small' => 'small', 
        'Default' => '', 
        'Large' => 'large', 
        'None' => 'remove'
      ], 
      'show' => 'switcher_style == \'accordion\''
    ], 
    'switcher_accordion_multiple' => [
      'label' => 'Options', 
      'description' => 'Accordion functionality is automatically turned off when autoplay is activated for the switcher.', 
      'type' => 'checkbox', 
      'text' => 'Allow multiple open items', 
      'enable' => '!switcher_autoplay', 
      'show' => 'switcher_style == \'accordion\''
    ], 
    'switcher_accordion_collapsible' => [
      'type' => 'checkbox', 
      'text' => 'Start with all items closed', 
      'enable' => '!switcher_autoplay', 
      'show' => 'switcher_style == \'accordion\''
    ], 
    'switcher_accordion_thumbnail' => [
      'type' => 'checkbox', 
      'text' => 'Show thumbnail image', 
      'show' => 'switcher_style == \'accordion\''
    ], 
    'switcher_thumbnail_width' => [
      'type' => 'number', 
      'attrs' => [
        'placeholder' => 'auto'
      ]
    ], 
    'switcher_thumbnail_height' => [
      'type' => 'number', 
      'attrs' => [
        'placeholder' => 'auto'
      ]
    ], 
    'switcher_thumbnail_icon_width' => [
      'type' => 'number', 
      'attrs' => [
        'placeholder' => 'auto'
      ]
    ], 
    'switcher_thumbnav_shrink' => [
      'label' => 'Responsiveness', 
      'description' => 'Shrink thumbnails if the container is too small.', 
      'type' => 'checkbox', 
      'text' => 'Shrink thumbnails', 
      'enable' => '!switcher_thumbnail_label', 
      'show' => 'switcher_style == \'thumbnav\''
    ], 
    'switcher_thumbnail_loading' => $config->get('fs_switcher.image_loading'), 
    'switcher_thumbnail_fetchpriority' => $config->get('fs_switcher.image_fetchpriority'), 
    'switcher_thumbnail_decoding' => $config->get('fs_switcher.image_decoding'), 
    'switcher_thumbnail_cache_disable' => $config->get('fs_switcher.cache_disable'), 
    'switcher_thumbnail_grid_column_gap' => $config->get('fs_switcher.grid_column_gap'), 
    'switcher_thumbnail_icon_color' => [
      'label' => 'Icon Color', 
      'description' => 'Select the color for thumbnail icons.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Muted' => 'muted', 
        'Emphasis' => 'emphasis', 
        'Primary' => 'primary', 
        'Secondary' => 'secondary', 
        'Success' => 'success', 
        'Warning' => 'warning', 
        'Danger' => 'danger'
      ]
    ], 
    'switcher_thumbnail_border' => [
      'label' => 'Border', 
      'description' => 'Select the image\'s border style.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Rounded' => 'rounded', 
        'Circle' => 'circle', 
        'Pill' => 'pill'
      ]
    ], 
    'switcher_thumbnail_svg_inline' => [
      'label' => 'Inline SVG', 
      'description' => 'Inject SVG images into the page markup to allow styling with CSS.', 
      'type' => 'checkbox', 
      'text' => 'Make SVG stylable with CSS'
    ], 
    'switcher_thumbnail_svg_color' => $config->get('fs_switcher.image_svg_color'), 
    'switcher_thumbnail_hover' => [
      'label' => 'Hover Image', 
      'description' => 'Show a different image on thumbnail hover.', 
      'type' => 'checkbox', 
      'text' => 'Show thumbnail hover image'
    ], 
    'switcher_thumbnail_label' => [
      'label' => 'Label', 
      'description' => 'Show or hide the label for each thumbnail navigation item.', 
      'type' => 'checkbox', 
      'text' => 'Show navigation label', 
      'show' => 'switcher_style == \'thumbnav\''
    ], 
    'switcher_thumbnail_label_position' => [
      'label' => 'Label Position', 
      'description' => 'Set the position of the navigation label relative to the thumbnail.', 
      'type' => 'select', 
      'options' => [
        'Right' => '', 
        'Top' => 'top', 
        'Bottom' => 'bottom'
      ], 
      'enable' => '$match(switcher_position, \'top|bottom\')', 
      'show' => 'switcher_style == \'thumbnav\' && switcher_thumbnail_label'
    ], 
    'switcher_thumbnail_label_visibility' => $config->get('fs_switcher.visibility'), 
    'switcher_thumbnail_text_color' => $config->get('fs_switcher.text_color'), 
    'position' => $config->get('builder.position'), 
    'position_left' => $config->get('builder.position_left'), 
    'position_right' => $config->get('builder.position_right'), 
    'position_top' => $config->get('builder.position_top'), 
    'position_bottom' => $config->get('builder.position_bottom'), 
    'position_z_index' => $config->get('builder.position_z_index'), 
    'blend' => $config->get('builder.blend'), 
    'margin_top' => [
      'label' => 'Margin Top', 
      'description' => 'Set the top margin.', 
      'type' => 'select', 
      'options' => [
        'Keep existing' => '', 
        'X-Small' => 'xsmall', 
        'Small' => 'small', 
        'Default' => 'default', 
        'Medium' => 'medium', 
        'Large' => 'large', 
        'X-Large' => 'xlarge', 
        'Remove' => 'remove', 
        'Auto' => 'auto'
      ], 
      'enable' => 'position != \'absolute\''
    ], 
    'margin_bottom' => [
      'label' => 'Margin Bottom', 
      'description' => 'Set the bottom margin.', 
      'type' => 'select', 
      'options' => [
        'Keep existing' => '', 
        'X-Small' => 'xsmall', 
        'Small' => 'small', 
        'Default' => 'default', 
        'Medium' => 'medium', 
        'Large' => 'large', 
        'X-Large' => 'xlarge', 
        'Remove' => 'remove', 
        'Auto' => 'auto'
      ], 
      'enable' => 'position != \'absolute\''
    ], 
    'maxwidth' => $config->get('builder.maxwidth'), 
    'maxwidth_breakpoint' => $config->get('builder.maxwidth_breakpoint'), 
    'block_align' => $config->get('builder.block_align'), 
    'block_align_breakpoint' => $config->get('builder.block_align_breakpoint'), 
    'block_align_fallback' => $config->get('builder.block_align_fallback'), 
    'text_align' => $config->get('builder.text_align_justify'), 
    'text_align_breakpoint' => $config->get('builder.text_align_breakpoint'), 
    'text_align_fallback' => $config->get('builder.text_align_justify_fallback'), 
    'animation' => $config->get('builder.animation'), 
    '_parallax_button' => $config->get('builder._parallax_button'), 
    'visibility' => $config->get('builder.visibility'), 
    'name' => $config->get('builder.name'), 
    'status' => $config->get('builder.status'), 
    'source' => $config->get('builder.source'), 
    'id' => $config->get('builder.id'), 
    'class' => $config->get('builder.cls'), 
    'attributes' => $config->get('builder.attrs'), 
    'css' => [
      'label' => 'CSS', 
      'description' => '<code>Old class names deprecated - removal in v2.0.0. Update your CSS.</code><br><br>The following selectors are automatically prefixed for this element: <code>.el-element</code> (scoped replacement for <code>.fs-switcher</code>), <code>.fs-switcher__grid</code>, <code>.fs-switcher__nav-container</code>, <code>.fs-switcher__nav</code>, <code>.fs-switcher__nav--horizontal</code>, <code>.fs-switcher__nav--vertical</code>, <code>.fs-switcher__nav-item</code>, <code>.el-nav-item--x</code>, <code>.fs-switcher__nav-item-link</code>, <code>.fs-switcher__nav-item-grid</code>, <code>.fs-switcher__nav-item-image-cell</code>, <code>.fs-switcher__nav-item-image-wrapper</code>, <code>.fs-switcher__nav-item-image</code>, <code>.fs-switcher__nav-item-image--hover</code>, <code>.fs-switcher__nav-item-icon</code>, <code>.fs-switcher__nav-item-label-cell</code>, <code>.fs-switcher__items-container</code>, <code>.fs-switcher__item</code>, <code>.el-item--x</code>, <code>.fs-switcher__item-sublayout</code>.', 
      'type' => 'editor', 
      'editor' => 'code', 
      'mode' => 'css', 
      'attrs' => [
        'debounce' => 500, 
        'hints' => ['.el-element', '.fs-switcher__grid', '.fs-switcher__nav-container', '.fs-switcher__nav', '.fs-switcher__nav--horizontal', '.fs-switcher__nav--vertical', '.fs-switcher__nav-item', '.el-nav-item--x', '.fs-switcher__nav-item-link', '.fs-switcher__nav-item-grid', '.fs-switcher__nav-item-image-cell', '.fs-switcher__nav-item-image-wrapper', '.fs-switcher__nav-item-image', '.fs-switcher__nav-item-image--hover', '.fs-switcher__nav-item-icon', '.fs-switcher__nav-item-label-cell', '.fs-switcher__items-container', '.fs-switcher__item', '.el-item--x', '.fs-switcher__item-sublayout']
      ]
    ], 
    'transform' => $config->get('builder.transform')
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Content', 
          'fields' => ['content']
        ], [
          'title' => 'Settings', 
          'fields' => [[
              'label' => 'Switcher', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['switcher_animation', 'switcher_height', 'switcher_swipe_disable', 'switcher_scroll', 'switcher_scroll_offset', 'switcher_autoplay', '_fs_switcher_pro_autoplay']
            ], [
              'label' => 'Navigation', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['switcher_style', 'switcher_mode', 'switcher_mode_hover_trigger_click', 'switcher_nowrap', 'switcher_vertical_align', 'switcher_style_primary', 'switcher_grid_divider', 'switcher_position', 'switcher_grid_width', 'switcher_grid_column_gap', 'switcher_grid_row_gap', 'switcher_grid_breakpoint', 'switcher_align', 'position_sticky', 'position_sticky_offset', 'position_sticky_breakpoint', 'switcher_margin', 'switcher_accordion_padding', 'switcher_accordion_multiple', 'switcher_accordion_collapsible', 'switcher_accordion_thumbnail']
            ], [
              'label' => 'Thumbnail', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => [[
                  'label' => 'Width/Height/Icon', 
                  'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.', 
                  'type' => 'grid', 
                  'width' => '1-3', 
                  'fields' => ['switcher_thumbnail_width', 'switcher_thumbnail_height', 'switcher_thumbnail_icon_width']
                ], 'switcher_thumbnav_shrink', 'switcher_thumbnail_loading', 'switcher_thumbnail_fetchpriority', 'switcher_thumbnail_decoding', 'switcher_thumbnail_cache_disable', 'switcher_thumbnail_grid_column_gap', 'switcher_thumbnail_icon_color', 'switcher_thumbnail_border', 'switcher_thumbnail_svg_inline', 'switcher_thumbnail_svg_color', 'switcher_thumbnail_hover', 'switcher_thumbnail_label', 'switcher_thumbnail_label_position', 'switcher_thumbnail_label_visibility', 'switcher_thumbnail_text_color']
            ], [
              'label' => 'General', 
              'type' => 'group', 
              'fields' => ['position', 'position_left', 'position_right', 'position_top', 'position_bottom', 'position_z_index', 'blend', 'margin_top', 'margin_bottom', 'maxwidth', 'maxwidth_breakpoint', 'block_align', 'block_align_breakpoint', 'block_align_fallback', 'text_align', 'text_align_breakpoint', 'text_align_fallback', 'animation', '_parallax_button', 'visibility']
            ]]
        ], $config->get('builder.advanced')]
    ]
  ], 
  'panels' => [
    '_fs_switcher_pro_autoplay_settings' => [
      'title' => 'Autoplay', 
      'width' => 500, 
      'fields' => [
        'switcher_autoplay_interval' => [
          'label' => 'Interval', 
          'description' => 'Set the interval between automatic tab changes. Min: 2, Max: 60.', 
          'type' => 'range', 
          'attrs' => [
            'min' => 2, 
            'max' => 60, 
            'step' => 1, 
            'placeholder' => '7'
          ]
        ], 
        'switcher_autoplay_pause' => [
          'label' => 'Behaviour', 
          'type' => 'checkbox', 
          'text' => 'Pause autoplay on hover', 
          'enable' => 'switcher_autoplay'
        ], 
        'switcher_autoplay_progress' => [
          'type' => 'checkbox', 
          'divider' => true, 
          'text' => 'Show autoplay progress'
        ], 
        'switcher_autoplay_progress_position' => [
          'label' => 'Position', 
          'description' => 'Sets the display position of the autoplay progress bar.', 
          'type' => 'select', 
          'options' => [
            'Above Navigation' => 'nav-top', 
            'Below Navigation' => 'nav-bottom', 
            'Top' => 'item-top', 
            'Bottom' => 'item-bottom'
          ], 
          'enable' => 'switcher_autoplay_progress'
        ], 
        'switcher_autoplay_progress_height' => [
          'label' => 'Height', 
          'description' => 'Set the height of the autoplay progress bar. Min: 1, Max: 10.', 
          'type' => 'range', 
          'attrs' => [
            'min' => 1, 
            'max' => 10, 
            'step' => 1, 
            'placeholder' => '1'
          ], 
          'enable' => 'switcher_autoplay_progress'
        ], 
        'switcher_autoplay_progress_fill' => [
          'label' => 'Fill Color', 
          'description' => 'Select the color of the progress bar fill.', 
          'type' => 'color', 
          'attrs' => [
            'placeholder' => '#ccc'
          ], 
          'enable' => 'switcher_autoplay_progress'
        ], 
        'switcher_autoplay_progress_track' => [
          'label' => 'Background Color', 
          'description' => 'Select the color of the progress bar background.', 
          'type' => 'color', 
          'attrs' => [
            'placeholder' => '#eee'
          ], 
          'enable' => 'switcher_autoplay_progress'
        ], 
        'switcher_autoplay_progress_margin' => [
          'label' => 'Margin', 
          'description' => 'Set the vertical margin.', 
          'type' => 'select', 
          'options' => [
            'Small' => 'small', 
            'Default' => '', 
            'Medium' => 'medium', 
            'Large' => 'large', 
            'X-Large' => 'xlarge'
          ], 
          'enable' => 'switcher_autoplay_progress'
        ]
      ], 
      'fieldset' => [
        'default' => [
          'fields' => ['switcher_autoplay_interval', 'switcher_autoplay_pause', 'switcher_autoplay_progress', 'switcher_autoplay_progress_position', 'switcher_autoplay_progress_height', 'switcher_autoplay_progress_fill', 'switcher_autoplay_progress_track', 'switcher_autoplay_progress_margin']
        ]
      ]
    ]
  ]
];
