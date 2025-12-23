<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/fs-switcher/modules/element/switcher/pro/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'fs_switcher', 
  'title' => 'Switcher Pro', 
  'group' => 'Flart Studio', 
  'icon' => $filter->apply('url', 'images/icon.svg', $file), 
  'iconSmall' => $filter->apply('url', 'images/iconSmall.svg', $file), 
  'element' => true, 
  'container' => true, 
  'width' => 500, 
  'defaults' => [
    '_grids_count' => 6, 
    '_fieldsets_count' => 20, 
    '_grids_navigator' => 'all', 
    '_settings_navigator' => 'all', 
    'show_title' => true, 
    'show_meta' => true, 
    'show_content' => true, 
    'show_sublayout' => true, 
    'show_image' => true, 
    'show_link' => true, 
    'show_label' => true, 
    'show_thumbnail' => true, 
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
    'lightbox_bg_close' => true, 
    'title_align' => 'top', 
    'meta_style' => 'text-meta', 
    'meta_align' => 'below-title', 
    'sublayout_position' => 'below-content', 
    'sublayout_align' => 'top', 
    'sublayout_modal_close_position' => 'default', 
    'image_svg_color' => 'emphasis', 
    'image_align' => 'top', 
    'link_text' => 'Read more', 
    'link_style' => 'default', 
    'margin_top' => 'default', 
    'margin_bottom' => 'default'
  ], 
  'placeholder' => [
    'children' => [[
        'type' => 'fs_switcher_item', 
        'props' => []
      ], [
        'type' => 'fs_switcher_item', 
        'props' => []
      ], [
        'type' => 'fs_switcher_item', 
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
      'item' => 'fs_switcher_item', 
      'media' => [
        'type' => 'image', 
        'item' => [
          'title' => 'title', 
          'image' => 'src'
        ]
      ]
    ], 
    '_grids_navigator' => [
      'label' => 'Navigator', 
      'description' => 'Select a settings section to show while hiding all other.', 
      'type' => 'select', 
      'attrs' => [
        'style' => 'border: 1px dashed #3696f3;'
      ], 
      'options' => [
        'All Settings' => 'all', 
        'Fieldsets' => 'fieldsets', 
        'Grids' => 'grids'
      ]
    ], 
    '_settings_navigator' => [
      'label' => 'Navigator', 
      'description' => 'Select a settings section to show while hiding all other.', 
      'type' => 'select', 
      'attrs' => [
        'style' => 'border: 1px dashed #3696f3;'
      ], 
      'options' => [
        'All Settings' => 'all', 
        'Navigation' => 'navigation', 
        'Lightbox' => 'lightbox', 
        'Title' => 'title', 
        'Meta' => 'meta', 
        'Content' => 'content', 
        'Sublayout / Modal' => 'sublayout', 
        'Image' => 'image', 
        'Link' => 'link', 
        'General' => 'general'
      ]
    ], 
    'show_title' => [
      'label' => 'Display', 
      'type' => 'checkbox', 
      'text' => 'Show the title'
    ], 
    'show_meta' => [
      'type' => 'checkbox', 
      'text' => 'Show the meta text'
    ], 
    'show_content' => [
      'type' => 'checkbox', 
      'text' => 'Show the content'
    ], 
    'show_sublayout' => [
      'type' => 'checkbox', 
      'text' => 'Show the sublayout'
    ], 
    'show_image' => [
      'type' => 'checkbox', 
      'text' => 'Show the image'
    ], 
    'show_link' => [
      'type' => 'checkbox', 
      'text' => 'Show the link'
    ], 
    'show_label' => [
      'type' => 'checkbox', 
      'text' => 'Show the navigation label instead of title'
    ], 
    'show_thumbnail' => [
      'type' => 'checkbox', 
      'text' => 'Show the navigation thumbnail instead of the image'
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
    'lightbox' => [
      'label' => 'Lightbox', 
      'description' => 'Open images in a lightbox gallery when clicked.', 
      'type' => 'checkbox', 
      'text' => 'Enable lightbox gallery', 
      'enable' => 'show_image'
    ], 
    'lightbox_controls' => [
      'type' => 'checkbox', 
      'text' => 'Show controls always', 
      'enable' => 'lightbox'
    ], 
    'lightbox_counter' => [
      'type' => 'checkbox', 
      'text' => 'Show counter', 
      'enable' => 'lightbox'
    ], 
    'lightbox_bg_close' => [
      'type' => 'checkbox', 
      'text' => 'Close on background click', 
      'enable' => 'lightbox'
    ], 
    'lightbox_animation' => [
      'label' => 'Animation', 
      'description' => 'Select the transition between two slides.', 
      'type' => 'select', 
      'options' => [
        'Slide' => '', 
        'Fade' => 'fade', 
        'Scale' => 'scale'
      ], 
      'enable' => 'lightbox'
    ], 
    'lightbox_nav' => [
      'label' => 'Navigation', 
      'description' => 'Select the navigation type.', 
      'type' => 'select', 
      'options' => [
        'Slidenav' => '', 
        'Dotnav' => 'dotnav', 
        'Thumbnav' => 'thumbnav'
      ], 
      'enable' => 'lightbox'
    ], 
    'lightbox_image_width' => [
      'type' => 'number', 
      'attrs' => [
        'placeholder' => 'auto'
      ], 
      'enable' => 'lightbox'
    ], 
    'lightbox_image_height' => [
      'type' => 'number', 
      'attrs' => [
        'placeholder' => 'auto'
      ], 
      'enable' => 'lightbox'
    ], 
    'lightbox_image_orientation' => [
      'label' => 'Image Orientation', 
      'description' => 'Allow mixed portrait and landscape images. Width and height will adjust automatically.', 
      'type' => 'checkbox', 
      'text' => 'Allow mixed image orientations', 
      'enable' => 'lightbox'
    ], 
    'lightbox_text_color' => $config->get('fs_switcher.text_color'), 
    'title_display' => [
      'label' => 'Show Title', 
      'description' => 'Choose where to display the title: inside the panel, as a lightbox caption, or both.', 
      'type' => 'select', 
      'options' => [
        'Panel + Lightbox' => '', 
        'Panel only' => 'item', 
        'Lightbox only' => 'lightbox'
      ], 
      'enable' => 'show_title && lightbox'
    ], 
    'content_display' => [
      'label' => 'Show Content', 
      'description' => 'Choose where to display the content: inside the panel, as a lightbox caption, or both.', 
      'type' => 'select', 
      'options' => [
        'Panel + Lightbox' => '', 
        'Panel only' => 'item', 
        'Lightbox only' => 'lightbox'
      ], 
      'enable' => 'show_content && lightbox'
    ], 
    'title_limit' => $config->get('fs_switcher.limit'), 
    'title_limit_length' => $config->get('fs_switcher.limit_length'), 
    'title_style' => $config->get('fs_switcher.style'), 
    'title_link' => [
      'label' => 'Link', 
      'description' => 'Link the title if a link exists.', 
      'type' => 'checkbox', 
      'text' => 'Link title', 
      'enable' => '(title_display != \'lightbox\' && lightbox || !lightbox) && show_link'
    ], 
    'title_hover_style' => [
      'label' => 'Hover Style', 
      'description' => 'Set the hover style for a linked title.', 
      'type' => 'select', 
      'options' => [
        'None' => 'reset', 
        'Heading Link' => 'heading', 
        'Default Link' => ''
      ], 
      'enable' => '(title_display != \'lightbox\' && lightbox || !lightbox) && show_link && (title_link || panel_link)'
    ], 
    'title_decoration' => $config->get('fs_switcher.decoration'), 
    'title_font_family' => [
      'label' => 'Font Family', 
      'description' => 'Select an alternative font family.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Default' => 'default', 
        'Primary' => 'primary', 
        'Secondary' => 'secondary', 
        'Tertiary' => 'tertiary'
      ], 
      'enable' => '(title_display != \'lightbox\' && lightbox || !lightbox)'
    ], 
    'title_color' => $config->get('fs_switcher.color'), 
    'title_element' => $config->get('fs_switcher.html_element'), 
    'title_align' => [
      'label' => 'Alignment', 
      'description' => 'Align the title to the top or left in regards to the content.', 
      'type' => 'select', 
      'options' => [
        'Top' => 'top', 
        'Left' => 'left'
      ], 
      'enable' => '(title_display != \'lightbox\' && lightbox || !lightbox)'
    ], 
    'title_grid_width' => $config->get('fs_switcher.grid_width'), 
    'title_grid_column_gap' => $config->get('fs_switcher.grid_column_gap'), 
    'title_grid_row_gap' => $config->get('fs_switcher.grid_row_gap'), 
    'title_grid_breakpoint' => $config->get('fs_switcher.grid_breakpoint'), 
    'title_margin' => $config->get('fs_switcher.margin'), 
    'meta_limit' => $config->get('fs_switcher.limit'), 
    'meta_limit_length' => $config->get('fs_switcher.limit_length'), 
    'meta_style' => $config->get('fs_switcher.style'), 
    'meta_decoration' => $config->get('fs_switcher.decoration'), 
    'meta_color' => $config->get('fs_switcher.color'), 
    'meta_align' => [
      'label' => 'Alignment', 
      'description' => 'Align the meta text.', 
      'type' => 'select', 
      'options' => [
        'Above Title' => 'above-title', 
        'Below Title' => 'below-title', 
        'Above Content' => 'above-content', 
        'Below Content' => 'below-content'
      ]
    ], 
    'meta_element' => $config->get('fs_switcher.html_element'), 
    'meta_margin' => $config->get('fs_switcher.margin'), 
    'meta_visibility' => $config->get('fs_switcher.visibility'), 
    'content_limit' => $config->get('fs_switcher.limit'), 
    'content_limit_length' => $config->get('fs_switcher.limit_length'), 
    'content_style' => $config->get('fs_switcher.style'), 
    'content_color' => $config->get('fs_switcher.color'), 
    'content_align' => [
      'label' => 'Alignment', 
      'type' => 'checkbox', 
      'text' => 'Force left alignment', 
      'enable' => '(content_display != \'lightbox\' && lightbox || !lightbox)'
    ], 
    'content_dropcap' => [
      'label' => 'Drop Cap', 
      'description' => 'Display the first letter of the paragraph as a large initial.', 
      'type' => 'checkbox', 
      'text' => 'Enable drop cap', 
      'enable' => '(content_display != \'lightbox\' && lightbox || !lightbox)'
    ], 
    'content_column' => [
      'label' => 'Columns', 
      'description' => 'Set the number of text columns.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Halves' => '1-2', 
        'Thirds' => '1-3', 
        'Quarters' => '1-4', 
        'Fifths' => '1-5', 
        'Sixths' => '1-6'
      ], 
      'enable' => '(content_display != \'lightbox\' && lightbox || !lightbox)'
    ], 
    'content_column_divider' => [
      'label' => 'Divider', 
      'description' => 'Show a divider between text columns.', 
      'type' => 'checkbox', 
      'text' => 'Show dividers', 
      'enable' => 'content_column', 
      'show' => '(content_display != \'lightbox\' && lightbox || !lightbox)'
    ], 
    'content_column_breakpoint' => $config->get('fs_switcher.grid_breakpoint'), 
    'content_element' => $config->get('fs_switcher.html_element'), 
    'content_margin' => $config->get('fs_switcher.margin'), 
    'content_visibility' => $config->get('fs_switcher.visibility'), 
    'sublayout_mode' => [
      'label' => 'Mode', 
      'description' => 'Choose how the sublayout is displayed: inline (native), in a modal window, or in mixed mode where some sublayouts open in a modal while the rest display inline.', 
      'default' => 'native', 
      'type' => 'select', 
      'options' => [
        'Inline Content' => 'native', 
        'Modal Window' => 'modal', 
        'Mixed Mode' => 'mixed'
      ]
    ], 
    'sublayout_position' => [
      'label' => 'Position', 
      'description' => 'Select the area of the layout where the sublayout will appear.', 
      'type' => 'select', 
      'options' => [
        'Above Content' => 'above-content', 
        'Below Content' => 'below-content', 
        'Item Image Cell Top' => 'item-image-cell-top', 
        'Item Image Cell Bottom' => 'item-image-cell-bottom', 
        'Grid #1' => 'grid_1', 
        'Grid #2' => 'grid_2', 
        'Grid #3' => 'grid_3', 
        'Grid #4' => 'grid_4', 
        'Grid #5' => 'grid_5', 
        'Grid #6' => 'grid_6'
      ], 
      'show' => '$match(sublayout_mode, \'native|mixed\')'
    ], 
    'sublayout_align' => [
      'label' => 'Alignment', 
      'description' => 'Set how the sublayout is aligned within the chosen display position.', 
      'type' => 'select', 
      'options' => [
        'Top' => 'top', 
        'Bottom' => 'bottom'
      ], 
      'show' => '$match(sublayout_mode, \'native|mixed\')'
    ], 
    'sublayout_margin' => $config->get('fs_switcher.margin'), 
    'sublayout_modal_group' => [
      'label' => 'Grouping', 
      'description' => 'Define how sublayouts should be grouped within the modal: all in one or each separately.', 
      'default' => 'all', 
      'type' => 'select', 
      'options' => [
        'All Sublayouts in One Modal' => 'all', 
        'Each Sublayout Separately' => 'each'
      ], 
      'show' => 'sublayout_mode == \'modal\''
    ], 
    'sublayout_modal_group_custom' => [
      'label' => 'Sublayouts to Wrap', 
      'description' => 'Enter the sublayout numbers to wrap in a modal (comma-separated, e.g. 1, 2, 5).', 
      'attrs' => [
        'placeholder' => '1, 2, 5'
      ], 
      'show' => 'sublayout_mode == \'mixed\''
    ], 
    'sublayout_modal_width' => [
      'type' => 'number', 
      'attrs' => [
        'placeholder' => 'auto'
      ], 
      'enable' => '!sublayout_modal_full', 
      'show' => 'show_sublayout && $match(sublayout_mode, \'modal|mixed\')'
    ], 
    'sublayout_modal_height' => [
      'type' => 'number', 
      'attrs' => [
        'placeholder' => 'auto'
      ], 
      'enable' => '!sublayout_modal_full', 
      'show' => '$match(sublayout_mode, \'modal|mixed\')'
    ], 
    'sublayout_modal_full' => [
      'type' => 'checkbox', 
      'text' => 'Enable full screen mode', 
      'show' => '$match(sublayout_mode, \'modal|mixed\')'
    ], 
    'sublayout_modal_stack' => [
      'label' => 'Appearance', 
      'type' => 'checkbox', 
      'text' => 'Stack modals'
    ], 
    'sublayout_modal_close_position' => [
      'type' => 'checkbox', 
      'text' => 'Close outside', 
      'attrs' => [
        'true-value' => 'outside', 
        'false-value' => 'default'
      ], 
      'enable' => '!sublayout_modal_full'
    ], 
    'sublayout_modal_close_size' => [
      'type' => 'checkbox', 
      'text' => 'Large close', 
      'enable' => '!sublayout_modal_full'
    ], 
    'sublayout_modal_header' => [
      'type' => 'checkbox', 
      'text' => 'Modal header', 
      'enable' => 'sublayout_mode == \'modal\' && sublayout_modal_group == \'all\''
    ], 
    '_sublayout_modal_info_wrap_all' => [
      'label' => 'Integration', 
      'description' => 'Enable <code>Interactive Toggle</code> under the Switcher item link. Then, once enabled, activate <code>Automatic Modal Connect</code> to display all sublayouts in a single modal.', 
      'type' => 'description', 
      'show' => 'sublayout_mode == \'modal\' && sublayout_modal_group == \'all\''
    ], 
    '_sublayout_modal_info_wrap_each' => [
      'label' => 'Integration', 
      'description' => 'Enable <code>Interactive Toggle</code> under the Switcher item link to reveal the <code>Modal ID</code> field. Enter a unique base ID (e.g., <code>FirstItem</code>). Then, in each sublayout’s Advanced tab, assign a unique sublayout ID (e.g., <code>ModalDetails</code>). The full modal selector will be <code>modalID-sublayoutID</code> (e.g., <code>FirstItem-ModalDetails</code>).', 
      'type' => 'description', 
      'show' => 'sublayout_mode == \'modal\' && sublayout_modal_group == \'each\''
    ], 
    '_sublayout_modal_info_mixed' => [
      'label' => 'Integration', 
      'description' => 'Enable <code>Interactive Toggle</code> under the Switcher item link to reveal the <code>Modal ID</code> field. Enter a unique base ID (e.g., <code>FirstItem</code>). Then, for each selected sublayout, set a unique sublayout ID in its Advanced tab (e.g., <code>ModalDetails</code>). The full modal selector will be <code>modalID-sublayoutID</code> (e.g., <code>FirstItem-ModalDetails</code>).', 
      'type' => 'description', 
      'show' => 'sublayout_mode == \'mixed\''
    ], 
    'image_width' => [
      'type' => 'number', 
      'attrs' => [
        'placeholder' => 'auto'
      ]
    ], 
    'image_height' => [
      'type' => 'number', 
      'attrs' => [
        'placeholder' => 'auto'
      ]
    ], 
    'image_loading' => $config->get('fs_switcher.image_loading'), 
    'image_fetchpriority' => $config->get('fs_switcher.image_fetchpriority'), 
    'image_decoding' => $config->get('fs_switcher.image_decoding'), 
    'image_cache_disable' => $config->get('fs_switcher.cache_disable'), 
    'image_border' => [
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
    'image_box_shadow' => [
      'label' => 'Image Box Shadow', 
      'description' => 'Select the image\'s box shadow size.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Small' => 'small', 
        'Medium' => 'medium', 
        'Large' => 'large', 
        'X-Large' => 'xlarge'
      ]
    ], 
    'image_box_decoration' => [
      'label' => 'Box Decoration', 
      'description' => 'Select the image\'s box decoration style. Note: The Mask option is not supported by all styles and may have no visible effect.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Default' => 'default', 
        'Primary' => 'primary', 
        'Secondary' => 'secondary', 
        'Floating Shadow' => 'shadow', 
        'Mask' => 'mask'
      ]
    ], 
    'image_box_decoration_inverse' => [
      'type' => 'checkbox', 
      'text' => 'Inverse style', 
      'enable' => '$match(image_box_decoration, \'^(default|primary|secondary)$\')'
    ], 
    'image_link' => [
      'label' => 'Link', 
      'description' => 'Link the image if a link exists.', 
      'type' => 'checkbox', 
      'text' => 'Link image', 
      'enable' => 'show_link'
    ], 
    'image_transition' => [
      'label' => 'Hover Transition', 
      'description' => 'Set the hover transition for a linked image.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Scale Up' => 'scale-up', 
        'Scale Down' => 'scale-down'
      ], 
      'enable' => 'show_link && image_link'
    ], 
    'image_hover_box_shadow' => [
      'label' => 'Hover Box Shadow', 
      'description' => 'Select the image box shadow size on hover.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Small' => 'small', 
        'Medium' => 'medium', 
        'Large' => 'large', 
        'X-Large' => 'xlarge'
      ], 
      'enable' => 'show_link && image_link'
    ], 
    'image_align' => [
      'label' => 'Alignment', 
      'description' => 'Align the image to the top, left, right or place it between the title and the content.', 
      'type' => 'select', 
      'options' => [
        'Top' => 'top', 
        'Bottom' => 'bottom', 
        'Left' => 'left', 
        'Right' => 'right'
      ]
    ], 
    'image_grid_width' => $config->get('fs_switcher.grid_width'), 
    'image_grid_column_gap' => $config->get('fs_switcher.grid_column_gap'), 
    'image_grid_row_gap' => $config->get('fs_switcher.grid_row_gap'), 
    'image_grid_breakpoint' => $config->get('fs_switcher.grid_breakpoint'), 
    'image_vertical_align' => [
      'label' => 'Vertical Alignment', 
      'description' => 'Vertically center grid items.', 
      'type' => 'checkbox', 
      'text' => 'Center', 
      'show' => '$match(image_align, \'left|right\')'
    ], 
    'image_svg_inline' => [
      'label' => 'Inline SVG', 
      'description' => 'Inject SVG images into the page markup so that they can easily be styled with CSS.', 
      'type' => 'checkbox', 
      'text' => 'Make SVG stylable with CSS'
    ], 
    'image_svg_animate' => [
      'type' => 'checkbox', 
      'text' => 'Animate strokes', 
      'show' => 'image_svg_inline'
    ], 
    'image_svg_color' => $config->get('fs_switcher.image_svg_color'), 
    'image_text_color' => $config->get('fs_switcher.text_color'), 
    'image_margin' => $config->get('fs_switcher.margin'), 
    'image_visibility' => $config->get('fs_switcher.visibility'), 
    'link_text' => [
      'label' => 'Text', 
      'description' => 'Enter the text for the link.'
    ], 
    'link_aria_label' => [
      'label' => 'ARIA Label', 
      'description' => 'Enter a descriptive text label to make it accessible if the link has no visible text.'
    ], 
    'link_title' => [
      'label' => 'Title', 
      'description' => 'Sets the link title attribute for tooltip support.'
    ], 
    'link_target' => [
      'label' => 'Attributes', 
      'type' => 'checkbox', 
      'text' => 'Open in a new window'
    ], 
    'link_download' => [
      'type' => 'checkbox', 
      'text' => 'Force file download', 
      'enable' => 'show_link && !lightbox'
    ], 
    'link_rel_nofollow' => [
      'type' => 'checkbox', 
      'text' => 'Nofollow', 
      'enable' => 'show_link && !lightbox'
    ], 
    'link_rel_noreferrer' => [
      'type' => 'checkbox', 
      'text' => 'Noreferrer', 
      'enable' => 'show_link && !lightbox'
    ], 
    'link_rel_noopener' => [
      'type' => 'checkbox', 
      'text' => 'Noopener', 
      'enable' => 'show_link && !lightbox'
    ], 
    'link_rel_prefetch' => [
      'type' => 'checkbox', 
      'text' => 'Prefetch', 
      'enable' => 'show_link && !lightbox'
    ], 
    'link_style' => [
      'label' => 'Style', 
      'description' => 'Set the link style.', 
      'type' => 'select', 
      'options' => [
        'Button Default' => 'default', 
        'Button Primary' => 'primary', 
        'Button Secondary' => 'secondary', 
        'Button Danger' => 'danger', 
        'Button Text' => 'text', 
        'Link' => '', 
        'Link Muted' => 'link-muted', 
        'Link Text' => 'link-text'
      ]
    ], 
    'link_size' => [
      'label' => 'Button Size', 
      'description' => 'Set the button size.', 
      'type' => 'select', 
      'options' => [
        'Small' => 'small', 
        'Default' => '', 
        'Large' => 'large'
      ], 
      'enable' => 'link_style && !$match(link_style, \'link-muted|link-text\')'
    ], 
    'link_fullwidth' => [
      'type' => 'checkbox', 
      'text' => 'Full width button', 
      'enable' => 'link_style && !$match(link_style, \'link-muted|link-text\')'
    ], 
    'link_bellow_image' => [
      'type' => 'checkbox', 
      'description' => 'Available when item image aligned to the left or right.', 
      'text' => 'Display link below the image', 
      'enable' => 'link_style && !$match(link_style, \'link-muted|link-text\') && $match(image_align, \'left|right\')'
    ], 
    'link_margin' => $config->get('fs_switcher.margin'), 
    'link_visibility' => $config->get('fs_switcher.visibility'), 
    'show_fieldsets' => [
      'label' => 'Fieldsets', 
      'type' => 'checkbox', 
      'text' => 'Enable custom fieldsets'
    ], 
    'enable_fieldsets_mixed_width' => [
      'label' => 'Mixed Width', 
      'type' => 'checkbox', 
      'text' => 'Enable mixed width', 
      'show' => 'show_fieldsets'
    ], 
    '_fieldsets_count' => [
      'type' => 'range', 
      'attrs' => [
        'min' => 1, 
        'max' => 20, 
        'step' => 1, 
        'placeholder' => '1'
      ]
    ], 
    '_fs_switcher_pro_fieldset_1' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_1_settings', 
      'text' => 'Fieldset 1 Settings'
    ], 
    '_fs_switcher_pro_fieldset_2' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_2_settings', 
      'text' => 'Fieldset 2 Settings', 
      'show' => '_fieldsets_count >= 2'
    ], 
    '_fs_switcher_pro_fieldset_3' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_3_settings', 
      'text' => 'Fieldset 3 Settings', 
      'show' => '_fieldsets_count >= 3'
    ], 
    '_fs_switcher_pro_fieldset_4' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_4_settings', 
      'text' => 'Fieldset 4 Settings', 
      'show' => '_fieldsets_count >= 4'
    ], 
    '_fs_switcher_pro_fieldset_5' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_5_settings', 
      'text' => 'Fieldset 5 Settings', 
      'show' => '_fieldsets_count >= 5'
    ], 
    '_fs_switcher_pro_fieldset_6' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_6_settings', 
      'text' => 'Fieldset 6 Settings', 
      'show' => '_fieldsets_count >= 6'
    ], 
    '_fs_switcher_pro_fieldset_7' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_7_settings', 
      'text' => 'Fieldset 7 Settings', 
      'show' => '_fieldsets_count >= 7'
    ], 
    '_fs_switcher_pro_fieldset_8' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_8_settings', 
      'text' => 'Fieldset 8 Settings', 
      'show' => '_fieldsets_count >= 8'
    ], 
    '_fs_switcher_pro_fieldset_9' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_9_settings', 
      'text' => 'Fieldset 9 Settings', 
      'show' => '_fieldsets_count >= 9'
    ], 
    '_fs_switcher_pro_fieldset_10' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_10_settings', 
      'text' => 'Fieldset 10 Settings', 
      'show' => '_fieldsets_count >= 10'
    ], 
    '_fs_switcher_pro_fieldset_11' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_11_settings', 
      'text' => 'Fieldset 11 Settings', 
      'show' => '_fieldsets_count >= 11'
    ], 
    '_fs_switcher_pro_fieldset_12' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_12_settings', 
      'text' => 'Fieldset 12 Settings', 
      'show' => '_fieldsets_count >= 12'
    ], 
    '_fs_switcher_pro_fieldset_13' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_13_settings', 
      'text' => 'Fieldset 13 Settings', 
      'show' => '_fieldsets_count >= 13'
    ], 
    '_fs_switcher_pro_fieldset_14' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_14_settings', 
      'text' => 'Fieldset 14 Settings', 
      'show' => '_fieldsets_count >= 14'
    ], 
    '_fs_switcher_pro_fieldset_15' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_15_settings', 
      'text' => 'Fieldset 15 Settings', 
      'show' => '_fieldsets_count >= 15'
    ], 
    '_fs_switcher_pro_fieldset_16' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_16_settings', 
      'text' => 'Fieldset 16 Settings', 
      'show' => '_fieldsets_count >= 16'
    ], 
    '_fs_switcher_pro_fieldset_17' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_17_settings', 
      'text' => 'Fieldset 17 Settings', 
      'show' => '_fieldsets_count >= 17'
    ], 
    '_fs_switcher_pro_fieldset_18' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_18_settings', 
      'text' => 'Fieldset 18 Settings', 
      'show' => '_fieldsets_count >= 18'
    ], 
    '_fs_switcher_pro_fieldset_19' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_19_settings', 
      'text' => 'Fieldset 19 Settings', 
      'show' => '_fieldsets_count >= 19'
    ], 
    '_fs_switcher_pro_fieldset_20' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_fieldset_20_settings', 
      'text' => 'Fieldset 20 Settings', 
      'show' => '_fieldsets_count >= 20'
    ], 
    'show_fieldset_1' => [
      'label' => 'Display', 
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 1'
    ], 
    'show_fieldset_2' => [
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 2', 
      'show' => '_fieldsets_count >= 2'
    ], 
    'show_fieldset_3' => [
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 3', 
      'show' => '_fieldsets_count >= 3'
    ], 
    'show_fieldset_4' => [
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 4', 
      'show' => '_fieldsets_count >= 4'
    ], 
    'show_fieldset_5' => [
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 5', 
      'show' => '_fieldsets_count >= 5'
    ], 
    'show_fieldset_6' => [
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 6', 
      'show' => '_fieldsets_count >= 6'
    ], 
    'show_fieldset_7' => [
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 7', 
      'show' => '_fieldsets_count >= 7'
    ], 
    'show_fieldset_8' => [
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 8', 
      'show' => '_fieldsets_count >= 8'
    ], 
    'show_fieldset_9' => [
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 9', 
      'show' => '_fieldsets_count >= 9'
    ], 
    'show_fieldset_10' => [
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 10', 
      'show' => '_fieldsets_count >= 10'
    ], 
    'show_fieldset_11' => [
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 11', 
      'show' => '_fieldsets_count >= 11'
    ], 
    'show_fieldset_12' => [
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 12', 
      'show' => '_fieldsets_count >= 12'
    ], 
    'show_fieldset_13' => [
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 13', 
      'show' => '_fieldsets_count >= 13'
    ], 
    'show_fieldset_14' => [
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 14', 
      'show' => '_fieldsets_count >= 14'
    ], 
    'show_fieldset_15' => [
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 15', 
      'show' => '_fieldsets_count >= 15'
    ], 
    'show_fieldset_16' => [
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 16', 
      'show' => '_fieldsets_count >= 16'
    ], 
    'show_fieldset_17' => [
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 17', 
      'show' => '_fieldsets_count >= 17'
    ], 
    'show_fieldset_18' => [
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 18', 
      'show' => '_fieldsets_count >= 18'
    ], 
    'show_fieldset_19' => [
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 19', 
      'show' => '_fieldsets_count >= 19'
    ], 
    'show_fieldset_20' => [
      'type' => 'checkbox', 
      'text' => 'Show the fieldset 20', 
      'show' => '_fieldsets_count >= 20'
    ], 
    '_show_fieldsets_display_info' => [
      'type' => 'info', 
      'description' => 'Show or hide fieldsets without the need to delete the content itself.'
    ], 
    'show_multiple_grids' => [
      'label' => 'Layout', 
      'description' => 'Allow up to 6 grids instead of a single one for more complex layouts.', 
      'type' => 'checkbox', 
      'text' => 'Enable advanced mode'
    ], 
    '_grids_helper' => [
      'label' => 'Development', 
      'description' => 'Display grid borders and numbers for visual debugging during layout building.', 
      'type' => 'checkbox', 
      'text' => 'Highlight active grids'
    ], 
    '_grids_count' => [
      'type' => 'range', 
      'attrs' => [
        'min' => 1, 
        'max' => 6, 
        'step' => 1, 
        'placeholder' => '1'
      ], 
      'show' => 'show_multiple_grids'
    ], 
    '_fs_switcher_pro_grid_1' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_grid_1_settings', 
      'text' => 'Grid 1 Settings'
    ], 
    '_fs_switcher_pro_grid_2' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_grid_2_settings', 
      'text' => 'Grid 2 Settings', 
      'show' => 'show_multiple_grids && _grids_count >= 2'
    ], 
    '_fs_switcher_pro_grid_3' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_grid_3_settings', 
      'text' => 'Grid 3 Settings', 
      'show' => 'show_multiple_grids && _grids_count >= 3'
    ], 
    '_fs_switcher_pro_grid_4' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_grid_4_settings', 
      'text' => 'Grid 4 Settings', 
      'show' => 'show_multiple_grids && _grids_count >= 4'
    ], 
    '_fs_switcher_pro_grid_5' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_grid_5_settings', 
      'text' => 'Grid 5 Settings', 
      'show' => 'show_multiple_grids && _grids_count >= 5'
    ], 
    '_fs_switcher_pro_grid_6' => [
      'type' => 'button-panel', 
      'panel' => '_fs_switcher_pro_grid_6_settings', 
      'text' => 'Grid 6 Settings', 
      'show' => 'show_multiple_grids && _grids_count >= 6'
    ], 
    'show_grid_1' => [
      'type' => 'checkbox', 
      'label' => 'Display', 
      'text' => 'Show the grid 1', 
      'default' => true, 
      'enable' => NULL, 
      'show' => 'show_multiple_grids && _grids_count >= 2'
    ], 
    'show_grid_2' => [
      'type' => 'checkbox', 
      'text' => 'Show the grid 2', 
      'show' => 'show_multiple_grids && _grids_count >= 2'
    ], 
    'show_grid_3' => [
      'type' => 'checkbox', 
      'text' => 'Show the grid 3', 
      'show' => 'show_multiple_grids && _grids_count >= 3'
    ], 
    'show_grid_4' => [
      'type' => 'checkbox', 
      'text' => 'Show the grid 4', 
      'show' => 'show_multiple_grids && _grids_count >= 4'
    ], 
    'show_grid_5' => [
      'type' => 'checkbox', 
      'text' => 'Show the grid 5', 
      'show' => 'show_multiple_grids && _grids_count >= 5'
    ], 
    'show_grid_6' => [
      'type' => 'checkbox', 
      'text' => 'Show the grid 6', 
      'show' => 'show_multiple_grids && _grids_count >= 6'
    ], 
    '_show_grids_display_info' => [
      'type' => 'info', 
      'description' => 'Show or hide grids without the need to delete the content itself.', 
      'show' => 'show_multiple_grids && _grids_count >= 2'
    ], 
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
      'description' => '<code>Old class names deprecated - removal in v2.0.0. Update your CSS.</code><br><br>The following selectors are automatically prefixed for this element: <code>.el-element</code> (scoped replacement for <code>.fs-switcher</code>), <code>.fs-switcher__grid</code>, <code>.fs-switcher__nav-container</code>, <code>.fs-switcher__nav</code>, <code>.fs-switcher__nav--horizontal</code>, <code>.fs-switcher__nav--vertical</code>, <code>.fs-switcher__nav-item</code>, <code>.el-nav-item--x</code>, <code>.fs-switcher__nav-item-link</code>, <code>.fs-switcher__nav-item-grid</code>, <code>.fs-switcher__nav-item-image-cell</code>, <code>.fs-switcher__nav-item-image-wrapper</code>, <code>.fs-switcher__nav-item-image</code>, <code>.fs-switcher__nav-item-image--hover</code>, <code>.fs-switcher__nav-item-icon</code>, <code>.fs-switcher__nav-item-label-cell</code>, <code>.fs-switcher__items-container</code>, <code>.fs-switcher__item</code>, <code>.el-item--x</code>, <code>.fs-switcher__item-image-grid</code>, <code>.fs-switcher__item-image-cell</code>, <code>.fs-switcher__item-image</code>, <code>.fs-switcher__item-content-container</code>, <code>.fs-switcher__item-title-grid</code>, <code>.fs-switcher__item-title-cell</code>, <code>.fs-switcher__item-title</code>, <code>.fs-switcher__item-content-cell</code>, <code>.fs-switcher__item-meta</code>, <code>.fs-switcher__item-content</code>, <code>.fs-switcher__grid-divider</code>, <code>.fs-switcher__grid-container</code>, <code>.el-grid-container--x</code>, <code>.fs-switcher__grid</code>, <code>.el-grid--x</code>, <code>.fs-switcher__fieldset</code>, <code>.el-fieldset--x</code>, <code>.fs-switcher__fieldset-grid</code>, <code>.fs-switcher__fieldset-grid-dotnav</code>, <code>.fs-switcher__fieldset-grid-slidenav</code>, <code>.fs-switcher__fieldset-image-cell</code>, <code>.fs-switcher__fieldset-image</code>, <code>.el-image--x</code>, <code>.fs-switcher__fieldset-icon</code>, <code>.el-icon--x</code>, <code>.fs-switcher__fieldset-content-cell</code>, <code>.fs-switcher__fieldset-meta</code>, <code>.el-meta--x</code>, <code>.fs-switcher__fieldset-text</code>, <code>.el-text--x</code>, <code>.fs-switcher__fieldset-link</code>, <code>.el-link--x</code>, <code>.fs-switcher__item-link-container</code>, <code>.fs-switcher__item-link</code>, <code>.fs-switcher__item-link--lightbox</code>, <code>.fs-switcher__modal</code>, <code>.fs-switcher__modal-dialog</code>, <code>.fs-switcher__modal-close</code>, <code>.fs-switcher__modal-container</code>, <code>.fs-switcher__modal-header</code>, <code>.fs-switcher__modal-body</code>, <code>.fs-switcher__item-sublayout</code>.', 
      'type' => 'editor', 
      'editor' => 'code', 
      'mode' => 'css', 
      'attrs' => [
        'debounce' => 500, 
        'hints' => ['.el-element', '.fs-switcher__grid', '.fs-switcher__nav-container', '.fs-switcher__nav', '.fs-switcher__nav--horizontal', '.fs-switcher__nav--vertical', '.fs-switcher__nav-item', '.el-nav-item--x', '.fs-switcher__nav-item-link', '.fs-switcher__nav-item-grid', '.fs-switcher__nav-item-image-cell', '.fs-switcher__nav-item-image-wrapper', '.fs-switcher__nav-item-image', '.fs-switcher__nav-item-image--hover', '.fs-switcher__nav-item-icon', '.fs-switcher__nav-item-label-cell', '.fs-switcher__items-container', '.fs-switcher__item', '.el-item--x', '.fs-switcher__item-image-grid', '.fs-switcher__item-image-cell', '.fs-switcher__item-image', '.fs-switcher__item-content-container', '.fs-switcher__item-title-grid', '.fs-switcher__item-title-cell', '.fs-switcher__item-title', '.fs-switcher__item-content-cell', '.fs-switcher__item-meta', '.fs-switcher__item-content', '.fs-switcher__grid-divider', '.fs-switcher__grid-container', '.el-grid-container--x', '.fs-switcher__grid', '.el-grid--x', '.fs-switcher__fieldset', '.el-fieldset--x', '.fs-switcher__fieldset-grid', '.fs-switcher__fieldset-grid-dotnav', '.fs-switcher__fieldset-grid-slidenav', '.fs-switcher__fieldset-image-cell', '.fs-switcher__fieldset-image', '.el-image--x', '.fs-switcher__fieldset-icon', '.el-icon--x', '.fs-switcher__fieldset-content-cell', '.fs-switcher__fieldset-meta', '.el-meta--x', '.fs-switcher__fieldset-text', '.el-text--x', '.fs-switcher__fieldset-link', '.el-link--x', '.fs-switcher__item-link-container', '.fs-switcher__item-link', '.fs-switcher__item-link--lightbox', '.fs-switcher__modal', '.fs-switcher__modal-dialog', '.fs-switcher__modal-close', '.fs-switcher__modal-container', '.fs-switcher__modal-header', '.fs-switcher__modal-body', '.fs-switcher__item-sublayout']
      ]
    ], 
    'transform' => $config->get('builder.transform')
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Content', 
          'fields' => ['content', 'show_title', 'show_meta', 'show_content', 'show_sublayout', 'show_image', 'show_link', 'show_label', 'show_thumbnail']
        ], [
          'title' => 'Fields', 
          'fields' => [[
              'name' => '_switcher_pro_grids_navigator', 
              'divider' => true, 
              'type' => 'fields', 
              'fields' => ['_grids_navigator'], 
              'show' => 'show_fieldsets'
            ], [
              'label' => 'Fieldsets', 
              'type' => 'group', 
              'fields' => ['show_fieldsets', 'enable_fieldsets_mixed_width'], 
              'show' => '$match(_grids_navigator, \'fieldsets|all\')'
            ], [
              'name' => '_switcher_pro_fieldsets_panels', 
              'divider' => true, 
              'type' => 'fields', 
              'fields' => ['_fieldsets_count', '_fs_switcher_pro_fieldset_1', '_fs_switcher_pro_fieldset_2', '_fs_switcher_pro_fieldset_3', '_fs_switcher_pro_fieldset_4', '_fs_switcher_pro_fieldset_5', '_fs_switcher_pro_fieldset_6', '_fs_switcher_pro_fieldset_7', '_fs_switcher_pro_fieldset_8', '_fs_switcher_pro_fieldset_9', '_fs_switcher_pro_fieldset_10', '_fs_switcher_pro_fieldset_11', '_fs_switcher_pro_fieldset_12', '_fs_switcher_pro_fieldset_13', '_fs_switcher_pro_fieldset_14', '_fs_switcher_pro_fieldset_15', '_fs_switcher_pro_fieldset_16', '_fs_switcher_pro_fieldset_17', '_fs_switcher_pro_fieldset_18', '_fs_switcher_pro_fieldset_19', '_fs_switcher_pro_fieldset_20', 'show_fieldset_1', 'show_fieldset_2', 'show_fieldset_3', 'show_fieldset_4', 'show_fieldset_5', 'show_fieldset_6', 'show_fieldset_7', 'show_fieldset_8', 'show_fieldset_9', 'show_fieldset_10', 'show_fieldset_11', 'show_fieldset_12', 'show_fieldset_13', 'show_fieldset_14', 'show_fieldset_15', 'show_fieldset_16', 'show_fieldset_17', 'show_fieldset_18', 'show_fieldset_19', 'show_fieldset_20', '_show_fieldsets_display_info'], 
              'show' => 'show_fieldsets && $match(_grids_navigator, \'fieldsets|all\')'
            ], [
              'label' => 'Grids', 
              'type' => 'group', 
              'fields' => ['show_multiple_grids', '_grids_helper'], 
              'show' => 'show_fieldsets && $match(_grids_navigator, \'grids|all\')'
            ], [
              'name' => '_switcher_pro_grid_panels', 
              'divider' => true, 
              'type' => 'fields', 
              'fields' => ['_grids_count', '_fs_switcher_pro_grid_1', '_fs_switcher_pro_grid_2', '_fs_switcher_pro_grid_3', '_fs_switcher_pro_grid_4', '_fs_switcher_pro_grid_5', '_fs_switcher_pro_grid_6', 'show_grid_1', 'show_grid_2', 'show_grid_3', 'show_grid_4', 'show_grid_5', 'show_grid_6', '_show_grids_display_info'], 
              'show' => 'show_fieldsets && $match(_grids_navigator, \'grids|all\')'
            ]]
        ], [
          'title' => 'Settings', 
          'fields' => [[
              'name' => '_switcher_pro_settings_visibility_navigator', 
              'divider' => true, 
              'type' => 'fields', 
              'fields' => ['_settings_navigator']
            ], [
              'label' => 'Switcher', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['switcher_animation', 'switcher_height', 'switcher_swipe_disable', 'switcher_scroll', 'switcher_scroll_offset', 'switcher_autoplay', '_fs_switcher_pro_autoplay'], 
              'show' => '$match(_settings_navigator, \'navigation|all\')'
            ], [
              'label' => 'Navigation', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['switcher_style', 'switcher_mode', 'switcher_mode_hover_trigger_click', 'switcher_nowrap', 'switcher_vertical_align', 'switcher_style_primary', 'switcher_grid_divider', 'switcher_position', 'switcher_grid_width', 'switcher_grid_column_gap', 'switcher_grid_row_gap', 'switcher_grid_breakpoint', 'switcher_align', 'position_sticky', 'position_sticky_offset', 'position_sticky_breakpoint', 'switcher_margin', 'switcher_accordion_padding', 'switcher_accordion_multiple', 'switcher_accordion_collapsible', 'switcher_accordion_thumbnail'], 
              'show' => '$match(_settings_navigator, \'navigation|all\')'
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
                ], 'switcher_thumbnav_shrink', 'switcher_thumbnail_loading', 'switcher_thumbnail_fetchpriority', 'switcher_thumbnail_decoding', 'switcher_thumbnail_cache_disable', 'switcher_thumbnail_grid_column_gap', 'switcher_thumbnail_icon_color', 'switcher_thumbnail_border', 'switcher_thumbnail_svg_inline', 'switcher_thumbnail_svg_color', 'switcher_thumbnail_hover', 'switcher_thumbnail_label', 'switcher_thumbnail_label_position', 'switcher_thumbnail_label_visibility', 'switcher_thumbnail_text_color'], 
              'show' => '(switcher_style == \'thumbnav\' || (switcher_style == \'accordion\' && switcher_accordion_thumbnail)) && $match(_settings_navigator, \'navigation|all\')'
            ], [
              'label' => 'Lightbox', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['lightbox', 'lightbox_controls', 'lightbox_counter', 'lightbox_bg_close', 'lightbox_animation', 'lightbox_nav', [
                  'label' => 'Image Width/Height', 
                  'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.', 
                  'type' => 'grid', 
                  'width' => '1-2', 
                  'fields' => ['lightbox_image_width', 'lightbox_image_height']
                ], 'lightbox_image_orientation', 'lightbox_text_color', 'title_display', 'content_display'], 
              'show' => 'show_image && $match(_settings_navigator, \'lightbox|all\')'
            ], [
              'label' => 'Title', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['title_limit', 'title_limit_length', 'title_style', 'title_link', 'title_hover_style', 'title_decoration', 'title_font_family', 'title_color', 'title_element', 'title_align', 'title_grid_width', 'title_grid_column_gap', 'title_grid_row_gap', 'title_grid_breakpoint', 'title_margin'], 
              'show' => 'show_title && $match(_settings_navigator, \'title|all\')'
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_limit', 'meta_limit_length', 'meta_style', 'meta_decoration', 'meta_color', 'meta_align', 'meta_element', 'meta_margin', 'meta_visibility'], 
              'show' => 'show_meta && $match(_settings_navigator, \'meta|all\')'
            ], [
              'label' => 'Content', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['content_limit', 'content_limit_length', 'content_style', 'content_color', 'content_align', 'content_dropcap', 'content_column', 'content_column_divider', 'content_column_breakpoint', 'content_element', 'content_margin', 'content_visibility'], 
              'show' => 'show_content && $match(_settings_navigator, \'content|all\')'
            ], [
              'label' => 'Sublayout', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['sublayout_mode', 'sublayout_position', 'sublayout_align', 'sublayout_margin'], 
              'show' => 'show_sublayout && $match(_settings_navigator, \'sublayout|all\')'
            ], [
              'label' => 'Modal', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['sublayout_modal_group', 'sublayout_modal_group_custom', 'sublayout_modal_stack', 'sublayout_modal_close_position', 'sublayout_modal_close_size', 'sublayout_modal_header', [
                  'label' => 'Width/Height', 
                  'description' => 'Set width and Height for the modal content window.', 
                  'type' => 'grid', 
                  'width' => '1-2', 
                  'fields' => ['sublayout_modal_width', 'sublayout_modal_height']
                ], 'sublayout_modal_full'], 
              'show' => 'show_sublayout && $match(sublayout_mode, \'modal|mixed\') && $match(_settings_navigator, \'sublayout|all\')'
            ], [
              'name' => '_switcher_pro_modal_integration_description', 
              'type' => 'fields', 
              'divider' => true, 
              'fields' => ['_sublayout_modal_info_wrap_all', '_sublayout_modal_info_wrap_each', '_sublayout_modal_info_mixed'], 
              'show' => 'show_sublayout && $match(sublayout_mode, \'modal|mixed\') && $match(_settings_navigator, \'sublayout|all\')'
            ], [
              'label' => 'Image', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => [[
                  'label' => 'Width/Height', 
                  'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.', 
                  'type' => 'grid', 
                  'width' => '1-2', 
                  'fields' => ['image_width', 'image_height']
                ], 'image_loading', 'image_fetchpriority', 'image_decoding', 'image_cache_disable', 'image_border', 'image_box_shadow', 'image_box_decoration', 'image_box_decoration_inverse', 'image_link', 'image_transition', 'image_hover_box_shadow', 'image_align', 'image_grid_width', 'image_grid_column_gap', 'image_grid_row_gap', 'image_grid_breakpoint', 'image_vertical_align', 'image_svg_inline', 'image_svg_animate', 'image_svg_color', 'image_text_color', 'image_margin', 'image_visibility'], 
              'show' => 'show_image && $match(_settings_navigator, \'image|all\')'
            ], [
              'label' => 'Link', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['link_text', 'link_aria_label', 'link_title', 'link_target', 'link_download', 'link_rel_nofollow', 'link_rel_noreferrer', 'link_rel_noopener', 'link_rel_prefetch', 'link_style', 'link_size', 'link_fullwidth', 'link_bellow_image', 'link_margin', 'link_visibility'], 
              'show' => 'show_link && $match(_settings_navigator, \'link|all\')'
            ], [
              'label' => 'General', 
              'type' => 'group', 
              'fields' => ['position', 'position_left', 'position_right', 'position_top', 'position_bottom', 'position_z_index', 'blend', 'margin_top', 'margin_bottom', 'maxwidth', 'maxwidth_breakpoint', 'block_align', 'block_align_breakpoint', 'block_align_fallback', 'text_align', 'text_align_breakpoint', 'text_align_fallback', 'animation', '_parallax_button', 'visibility'], 
              'show' => '$match(_settings_navigator, \'general|all\')'
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
    ], 
    '_fs_switcher_pro_grid_slider_settings' => [
      'title' => 'Slider', 
      'width' => 500, 
      'fields' => [
        'slider_sets' => [
          'label' => 'Sets', 
          'description' => 'Group items into sets. The number of items within a set depends on the defined item width, e.g. <i>33%</i> means that each set contains 3 items.', 
          'type' => 'checkbox', 
          'text' => 'Slide all visible items at once'
        ], 
        'slider_center' => [
          'label' => 'Center', 
          'type' => 'checkbox', 
          'text' => 'Center the active slide'
        ], 
        'slider_finite' => [
          'label' => 'Finite', 
          'type' => 'checkbox', 
          'text' => 'Disable infinite scrolling'
        ], 
        'slider_velocity' => [
          'label' => 'Velocity', 
          'description' => 'Set the velocity in pixels per millisecond.', 
          'type' => 'range', 
          'attrs' => [
            'min' => 0.2, 
            'max' => 3, 
            'step' => 0.1, 
            'placeholder' => '1'
          ]
        ], 
        'slider_autoplay' => [
          'label' => 'Autoplay', 
          'type' => 'checkbox', 
          'text' => 'Enable autoplay'
        ], 
        'slider_autoplay_pause' => [
          'type' => 'checkbox', 
          'text' => 'Pause autoplay on hover', 
          'enable' => 'slider_autoplay'
        ], 
        'slider_autoplay_interval' => [
          'label' => 'Interval', 
          'description' => 'Set the autoplay interval in seconds.', 
          'type' => 'range', 
          'attrs' => [
            'min' => 5, 
            'max' => 15, 
            'step' => 1, 
            'placeholder' => '7'
          ], 
          'enable' => 'slider_autoplay'
        ], 
        'slider_dotnav' => [
          'label' => 'Navigation', 
          'description' => 'Select the navigation type.', 
          'default' => 'dotnav', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'Dotnav' => 'dotnav'
          ]
        ], 
        'slider_dotnav_align' => [
          'label' => 'Position', 
          'description' => 'Align the navigation\'s items.', 
          'default' => 'center', 
          'type' => 'select', 
          'options' => [
            'Left' => 'left', 
            'Center' => 'center', 
            'Right' => 'right'
          ], 
          'enable' => 'nav'
        ], 
        'slider_dotnav_margin' => [
          'label' => 'Margin', 
          'description' => 'Set the vertical margin.', 
          'type' => 'select', 
          'options' => [
            'Small' => 'small', 
            'Default' => '', 
            'Medium' => 'medium'
          ], 
          'enable' => 'nav'
        ], 
        'slider_dotnav_breakpoint' => [
          'label' => 'Breakpoint', 
          'description' => 'Display the navigation only on this device width and larger.', 
          'type' => 'select', 
          'options' => $config->get('fs_switcher.grid_breakpoint_options'), 
          'enable' => 'nav'
        ], 
        'slider_dotnav_color' => [
          'label' => 'Color', 
          'description' => 'Set light or dark color mode.', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'Light' => 'light', 
            'Dark' => 'dark'
          ], 
          'enable' => 'nav'
        ], 
        'slider_slidenav' => [
          'label' => 'Position', 
          'description' => 'Select the position of the slidenav.', 
          'default' => 'default', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'Default' => 'default'
          ]
        ], 
        'slider_slidenav_hover' => [
          'type' => 'checkbox', 
          'text' => 'Show on hover only', 
          'enable' => 'slidenav'
        ], 
        'slider_slidenav_large' => [
          'type' => 'checkbox', 
          'text' => 'Larger style', 
          'enable' => 'slidenav'
        ], 
        'slider_slidenav_margin' => [
          'label' => 'Margin', 
          'description' => 'Apply a margin between the slidenav and the slider container.', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'Small' => 'small', 
            'Medium' => 'medium', 
            'Large' => 'large'
          ], 
          'enable' => 'slidenav'
        ], 
        'slider_slidenav_breakpoint' => [
          'label' => 'Breakpoint', 
          'description' => 'Display the slidenav only on this device width and larger.', 
          'type' => 'select', 
          'default' => 'l', 
          'options' => $config->get('fs_switcher.grid_breakpoint_options'), 
          'enable' => 'slidenav'
        ], 
        'slider_slidenav_color' => [
          'label' => 'Color', 
          'description' => 'Set light or dark color mode.', 
          'type' => 'select', 
          'options' => [
            'None' => '', 
            'Light' => 'light', 
            'Dark' => 'dark'
          ], 
          'enable' => 'slidenav'
        ]
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Animation', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['slider_sets', 'slider_center', 'slider_finite', 'slider_velocity', 'slider_autoplay', 'slider_autoplay_pause', 'slider_autoplay_interval']
            ], [
              'label' => 'Navigation', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['slider_dotnav', 'slider_dotnav_align', 'slider_dotnav_margin', 'slider_dotnav_breakpoint', 'slider_dotnav_color']
            ], [
              'label' => 'Slidenav', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['slider_slidenav', 'slider_slidenav_hover', 'slider_slidenav_large', 'slider_slidenav_margin', 'slider_slidenav_breakpoint', 'slider_slidenav_color']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_1_settings' => [
      'title' => 'Fieldset 1', 
      'width' => 500, 
      'fields' => [
        'fieldset_1_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_1' => $config->get('fs_switcher.show_text'), 
        'show_meta_1' => $config->get('fs_switcher.show_meta'), 
        'show_image_1' => $config->get('fs_switcher.show_image'), 
        'show_link_1' => $config->get('fs_switcher.show_link'), 
        'fieldset_1_visibility' => $config->get('fs_switcher.visibility'), 
        'text_1_limit' => $config->get('fs_switcher.limit'), 
        'text_1_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_1_style' => $config->get('fs_switcher.style'), 
        'text_1_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_1_color' => $config->get('fs_switcher.color'), 
        'text_1_element' => $config->get('fs_switcher.html_element'), 
        'text_1_margin' => $config->get('fs_switcher.margin'), 
        'meta_1_position' => $config->get('fs_switcher.meta_position'), 
        'meta_1_style' => $config->get('fs_switcher.style'), 
        'meta_1_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_1_color' => $config->get('fs_switcher.color'), 
        'meta_1_element' => $config->get('fs_switcher.html_element'), 
        'meta_1_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_1_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_1_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_1_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_1_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_1_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_1_target', 'show_text_1', 'show_meta_1', 'show_image_1', 'show_link_1', 'fieldset_1_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_1_limit', 'text_1_limit_length', 'text_1_style', 'text_1_hover_style', 'text_1_color', 'text_1_element', 'text_1_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_1_position', 'meta_1_style', 'meta_1_hover_style', 'meta_1_color', 'meta_1_element', 'meta_1_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_1_mixed_width_default', 'fieldset_1_mixed_width_small', 'fieldset_1_mixed_width_medium', 'fieldset_1_mixed_width_large', 'fieldset_1_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_2_settings' => [
      'title' => 'Fieldset 2', 
      'width' => 500, 
      'fields' => [
        'fieldset_2_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_2' => $config->get('fs_switcher.show_text'), 
        'show_meta_2' => $config->get('fs_switcher.show_meta'), 
        'show_image_2' => $config->get('fs_switcher.show_image'), 
        'show_link_2' => $config->get('fs_switcher.show_link'), 
        'fieldset_2_visibility' => $config->get('fs_switcher.visibility'), 
        'text_2_limit' => $config->get('fs_switcher.limit'), 
        'text_2_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_2_style' => $config->get('fs_switcher.style'), 
        'text_2_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_2_color' => $config->get('fs_switcher.color'), 
        'text_2_element' => $config->get('fs_switcher.html_element'), 
        'text_2_margin' => $config->get('fs_switcher.margin'), 
        'meta_2_position' => $config->get('fs_switcher.meta_position'), 
        'meta_2_style' => $config->get('fs_switcher.style'), 
        'meta_2_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_2_color' => $config->get('fs_switcher.color'), 
        'meta_2_element' => $config->get('fs_switcher.html_element'), 
        'meta_2_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_2_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_2_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_2_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_2_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_2_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_2_target', 'show_text_2', 'show_meta_2', 'show_image_2', 'show_link_2', 'fieldset_2_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_2_limit', 'text_2_limit_length', 'text_2_style', 'text_2_hover_style', 'text_2_color', 'text_2_element', 'text_2_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_2_position', 'meta_2_style', 'meta_2_hover_style', 'meta_2_color', 'meta_2_element', 'meta_2_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_2_mixed_width_default', 'fieldset_2_mixed_width_small', 'fieldset_2_mixed_width_medium', 'fieldset_2_mixed_width_large', 'fieldset_2_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_3_settings' => [
      'title' => 'Fieldset 3', 
      'width' => 500, 
      'fields' => [
        'fieldset_3_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_3' => $config->get('fs_switcher.show_text'), 
        'show_meta_3' => $config->get('fs_switcher.show_meta'), 
        'show_image_3' => $config->get('fs_switcher.show_image'), 
        'show_link_3' => $config->get('fs_switcher.show_link'), 
        'fieldset_3_visibility' => $config->get('fs_switcher.visibility'), 
        'text_3_limit' => $config->get('fs_switcher.limit'), 
        'text_3_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_3_style' => $config->get('fs_switcher.style'), 
        'text_3_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_3_color' => $config->get('fs_switcher.color'), 
        'text_3_element' => $config->get('fs_switcher.html_element'), 
        'text_3_margin' => $config->get('fs_switcher.margin'), 
        'meta_3_position' => $config->get('fs_switcher.meta_position'), 
        'meta_3_style' => $config->get('fs_switcher.style'), 
        'meta_3_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_3_color' => $config->get('fs_switcher.color'), 
        'meta_3_element' => $config->get('fs_switcher.html_element'), 
        'meta_3_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_3_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_3_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_3_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_3_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_3_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_3_target', 'show_text_3', 'show_meta_3', 'show_image_3', 'show_link_3', 'fieldset_3_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_3_limit', 'text_3_limit_length', 'text_3_style', 'text_3_hover_style', 'text_3_color', 'text_3_element', 'text_3_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_3_position', 'meta_3_style', 'meta_3_hover_style', 'meta_3_color', 'meta_3_element', 'meta_3_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_3_mixed_width_default', 'fieldset_3_mixed_width_small', 'fieldset_3_mixed_width_medium', 'fieldset_3_mixed_width_large', 'fieldset_3_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_4_settings' => [
      'title' => 'Fieldset 4', 
      'width' => 500, 
      'fields' => [
        'fieldset_4_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_4' => $config->get('fs_switcher.show_text'), 
        'show_meta_4' => $config->get('fs_switcher.show_meta'), 
        'show_image_4' => $config->get('fs_switcher.show_image'), 
        'show_link_4' => $config->get('fs_switcher.show_link'), 
        'fieldset_4_visibility' => $config->get('fs_switcher.visibility'), 
        'text_4_limit' => $config->get('fs_switcher.limit'), 
        'text_4_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_4_style' => $config->get('fs_switcher.style'), 
        'text_4_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_4_color' => $config->get('fs_switcher.color'), 
        'text_4_element' => $config->get('fs_switcher.html_element'), 
        'text_4_margin' => $config->get('fs_switcher.margin'), 
        'meta_4_position' => $config->get('fs_switcher.meta_position'), 
        'meta_4_style' => $config->get('fs_switcher.style'), 
        'meta_4_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_4_color' => $config->get('fs_switcher.color'), 
        'meta_4_element' => $config->get('fs_switcher.html_element'), 
        'meta_4_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_4_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_4_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_4_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_4_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_4_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_4_target', 'show_text_4', 'show_meta_4', 'show_image_4', 'show_link_4', 'fieldset_4_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_4_limit', 'text_4_limit_length', 'text_4_style', 'text_4_hover_style', 'text_4_color', 'text_4_element', 'text_4_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_4_position', 'meta_4_style', 'meta_4_hover_style', 'meta_4_color', 'meta_4_element', 'meta_4_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_4_mixed_width_default', 'fieldset_4_mixed_width_small', 'fieldset_4_mixed_width_medium', 'fieldset_4_mixed_width_large', 'fieldset_4_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_5_settings' => [
      'title' => 'Fieldset 5', 
      'width' => 500, 
      'fields' => [
        'fieldset_5_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_5' => $config->get('fs_switcher.show_text'), 
        'show_meta_5' => $config->get('fs_switcher.show_meta'), 
        'show_image_5' => $config->get('fs_switcher.show_image'), 
        'show_link_5' => $config->get('fs_switcher.show_link'), 
        'fieldset_5_visibility' => $config->get('fs_switcher.visibility'), 
        'text_5_limit' => $config->get('fs_switcher.limit'), 
        'text_5_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_5_style' => $config->get('fs_switcher.style'), 
        'text_5_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_5_color' => $config->get('fs_switcher.color'), 
        'text_5_element' => $config->get('fs_switcher.html_element'), 
        'text_5_margin' => $config->get('fs_switcher.margin'), 
        'meta_5_position' => $config->get('fs_switcher.meta_position'), 
        'meta_5_style' => $config->get('fs_switcher.style'), 
        'meta_5_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_5_color' => $config->get('fs_switcher.color'), 
        'meta_5_element' => $config->get('fs_switcher.html_element'), 
        'meta_5_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_5_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_5_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_5_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_5_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_5_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_5_target', 'show_text_5', 'show_meta_5', 'show_image_5', 'show_link_5', 'fieldset_5_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_5_limit', 'text_5_limit_length', 'text_5_style', 'text_5_hover_style', 'text_5_color', 'text_5_element', 'text_5_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_5_position', 'meta_5_style', 'meta_5_hover_style', 'meta_5_color', 'meta_5_element', 'meta_5_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_5_mixed_width_default', 'fieldset_5_mixed_width_small', 'fieldset_5_mixed_width_medium', 'fieldset_5_mixed_width_large', 'fieldset_5_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_6_settings' => [
      'title' => 'Fieldset 6', 
      'width' => 500, 
      'fields' => [
        'fieldset_6_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_6' => $config->get('fs_switcher.show_text'), 
        'show_meta_6' => $config->get('fs_switcher.show_meta'), 
        'show_image_6' => $config->get('fs_switcher.show_image'), 
        'show_link_6' => $config->get('fs_switcher.show_link'), 
        'fieldset_6_visibility' => $config->get('fs_switcher.visibility'), 
        'text_6_limit' => $config->get('fs_switcher.limit'), 
        'text_6_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_6_style' => $config->get('fs_switcher.style'), 
        'text_6_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_6_color' => $config->get('fs_switcher.color'), 
        'text_6_element' => $config->get('fs_switcher.html_element'), 
        'text_6_margin' => $config->get('fs_switcher.margin'), 
        'meta_6_position' => $config->get('fs_switcher.meta_position'), 
        'meta_6_style' => $config->get('fs_switcher.style'), 
        'meta_6_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_6_color' => $config->get('fs_switcher.color'), 
        'meta_6_element' => $config->get('fs_switcher.html_element'), 
        'meta_6_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_6_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_6_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_6_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_6_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_6_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_6_target', 'show_text_6', 'show_meta_6', 'show_image_6', 'show_link_6', 'fieldset_6_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_6_limit', 'text_6_limit_length', 'text_6_style', 'text_6_hover_style', 'text_6_color', 'text_6_element', 'text_6_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_6_position', 'meta_6_style', 'meta_6_hover_style', 'meta_6_color', 'meta_6_element', 'meta_6_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_6_mixed_width_default', 'fieldset_6_mixed_width_small', 'fieldset_6_mixed_width_medium', 'fieldset_6_mixed_width_large', 'fieldset_6_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_7_settings' => [
      'title' => 'Fieldset 7', 
      'width' => 500, 
      'fields' => [
        'fieldset_7_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_7' => $config->get('fs_switcher.show_text'), 
        'show_meta_7' => $config->get('fs_switcher.show_meta'), 
        'show_image_7' => $config->get('fs_switcher.show_image'), 
        'show_link_7' => $config->get('fs_switcher.show_link'), 
        'fieldset_7_visibility' => $config->get('fs_switcher.visibility'), 
        'text_7_limit' => $config->get('fs_switcher.limit'), 
        'text_7_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_7_style' => $config->get('fs_switcher.style'), 
        'text_7_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_7_color' => $config->get('fs_switcher.color'), 
        'text_7_element' => $config->get('fs_switcher.html_element'), 
        'text_7_margin' => $config->get('fs_switcher.margin'), 
        'meta_7_position' => $config->get('fs_switcher.meta_position'), 
        'meta_7_style' => $config->get('fs_switcher.style'), 
        'meta_7_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_7_color' => $config->get('fs_switcher.color'), 
        'meta_7_element' => $config->get('fs_switcher.html_element'), 
        'meta_7_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_7_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_7_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_7_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_7_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_7_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_7_target', 'show_text_7', 'show_meta_7', 'show_image_7', 'show_link_7', 'fieldset_7_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_7_limit', 'text_7_limit_length', 'text_7_style', 'text_7_hover_style', 'text_7_color', 'text_7_element', 'text_7_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_7_position', 'meta_7_style', 'meta_7_hover_style', 'meta_7_color', 'meta_7_element', 'meta_7_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_7_mixed_width_default', 'fieldset_7_mixed_width_small', 'fieldset_7_mixed_width_medium', 'fieldset_7_mixed_width_large', 'fieldset_7_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_8_settings' => [
      'title' => 'Fieldset 8', 
      'width' => 500, 
      'fields' => [
        'fieldset_8_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_8' => $config->get('fs_switcher.show_text'), 
        'show_meta_8' => $config->get('fs_switcher.show_meta'), 
        'show_image_8' => $config->get('fs_switcher.show_image'), 
        'show_link_8' => $config->get('fs_switcher.show_link'), 
        'fieldset_8_visibility' => $config->get('fs_switcher.visibility'), 
        'text_8_limit' => $config->get('fs_switcher.limit'), 
        'text_8_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_8_style' => $config->get('fs_switcher.style'), 
        'text_8_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_8_color' => $config->get('fs_switcher.color'), 
        'text_8_element' => $config->get('fs_switcher.html_element'), 
        'text_8_margin' => $config->get('fs_switcher.margin'), 
        'meta_8_position' => $config->get('fs_switcher.meta_position'), 
        'meta_8_style' => $config->get('fs_switcher.style'), 
        'meta_8_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_8_color' => $config->get('fs_switcher.color'), 
        'meta_8_element' => $config->get('fs_switcher.html_element'), 
        'meta_8_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_8_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_8_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_8_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_8_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_8_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_8_target', 'show_text_8', 'show_meta_8', 'show_image_8', 'show_link_8', 'fieldset_8_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_8_limit', 'text_8_limit_length', 'text_8_style', 'text_8_hover_style', 'text_8_color', 'text_8_element', 'text_8_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_8_position', 'meta_8_style', 'meta_8_hover_style', 'meta_8_color', 'meta_8_element', 'meta_8_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_8_mixed_width_default', 'fieldset_8_mixed_width_small', 'fieldset_8_mixed_width_medium', 'fieldset_8_mixed_width_large', 'fieldset_8_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_9_settings' => [
      'title' => 'Fieldset 9', 
      'width' => 500, 
      'fields' => [
        'fieldset_9_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_9' => $config->get('fs_switcher.show_text'), 
        'show_meta_9' => $config->get('fs_switcher.show_meta'), 
        'show_image_9' => $config->get('fs_switcher.show_image'), 
        'show_link_9' => $config->get('fs_switcher.show_link'), 
        'fieldset_9_visibility' => $config->get('fs_switcher.visibility'), 
        'text_9_limit' => $config->get('fs_switcher.limit'), 
        'text_9_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_9_style' => $config->get('fs_switcher.style'), 
        'text_9_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_9_color' => $config->get('fs_switcher.color'), 
        'text_9_element' => $config->get('fs_switcher.html_element'), 
        'text_9_margin' => $config->get('fs_switcher.margin'), 
        'meta_9_position' => $config->get('fs_switcher.meta_position'), 
        'meta_9_style' => $config->get('fs_switcher.style'), 
        'meta_9_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_9_color' => $config->get('fs_switcher.color'), 
        'meta_9_element' => $config->get('fs_switcher.html_element'), 
        'meta_9_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_9_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_9_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_9_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_9_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_9_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_9_target', 'show_text_9', 'show_meta_9', 'show_image_9', 'show_link_9', 'fieldset_9_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_9_limit', 'text_9_limit_length', 'text_9_style', 'text_9_hover_style', 'text_9_color', 'text_9_element', 'text_9_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_9_position', 'meta_9_style', 'meta_9_hover_style', 'meta_9_color', 'meta_9_element', 'meta_9_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_9_mixed_width_default', 'fieldset_9_mixed_width_small', 'fieldset_9_mixed_width_medium', 'fieldset_9_mixed_width_large', 'fieldset_9_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_10_settings' => [
      'title' => 'Fieldset 10', 
      'width' => 500, 
      'fields' => [
        'fieldset_10_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_10' => $config->get('fs_switcher.show_text'), 
        'show_meta_10' => $config->get('fs_switcher.show_meta'), 
        'show_image_10' => $config->get('fs_switcher.show_image'), 
        'show_link_10' => $config->get('fs_switcher.show_link'), 
        'fieldset_10_visibility' => $config->get('fs_switcher.visibility'), 
        'text_10_limit' => $config->get('fs_switcher.limit'), 
        'text_10_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_10_style' => $config->get('fs_switcher.style'), 
        'text_10_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_10_color' => $config->get('fs_switcher.color'), 
        'text_10_element' => $config->get('fs_switcher.html_element'), 
        'text_10_margin' => $config->get('fs_switcher.margin'), 
        'meta_10_position' => $config->get('fs_switcher.meta_position'), 
        'meta_10_style' => $config->get('fs_switcher.style'), 
        'meta_10_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_10_color' => $config->get('fs_switcher.color'), 
        'meta_10_element' => $config->get('fs_switcher.html_element'), 
        'meta_10_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_10_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_10_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_10_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_10_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_10_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_10_target', 'show_text_10', 'show_meta_10', 'show_image_10', 'show_link_10', 'fieldset_10_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_10_limit', 'text_10_limit_length', 'text_10_style', 'text_10_hover_style', 'text_10_color', 'text_10_element', 'text_10_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_10_position', 'meta_10_style', 'meta_10_hover_style', 'meta_10_color', 'meta_10_element', 'meta_10_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_10_mixed_width_default', 'fieldset_10_mixed_width_small', 'fieldset_10_mixed_width_medium', 'fieldset_10_mixed_width_large', 'fieldset_10_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_11_settings' => [
      'title' => 'Fieldset 11', 
      'width' => 500, 
      'fields' => [
        'fieldset_11_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_11' => $config->get('fs_switcher.show_text'), 
        'show_meta_11' => $config->get('fs_switcher.show_meta'), 
        'show_image_11' => $config->get('fs_switcher.show_image'), 
        'show_link_11' => $config->get('fs_switcher.show_link'), 
        'fieldset_11_visibility' => $config->get('fs_switcher.visibility'), 
        'text_11_limit' => $config->get('fs_switcher.limit'), 
        'text_11_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_1_style' => $config->get('fs_switcher.style'), 
        'text_11_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_11_color' => $config->get('fs_switcher.color'), 
        'text_11_element' => $config->get('fs_switcher.html_element'), 
        'text_11_margin' => $config->get('fs_switcher.margin'), 
        'meta_11_position' => $config->get('fs_switcher.meta_position'), 
        'meta_11_style' => $config->get('fs_switcher.style'), 
        'meta_11_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_11_color' => $config->get('fs_switcher.color'), 
        'meta_11_element' => $config->get('fs_switcher.html_element'), 
        'meta_11_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_11_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_11_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_11_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_11_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_11_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_11_target', 'show_text_11', 'show_meta_11', 'show_image_11', 'show_link_11', 'fieldset_11_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_11_limit', 'text_11_limit_length', 'text_11_style', 'text_11_hover_style', 'text_11_color', 'text_11_element', 'text_11_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_11_position', 'meta_11_style', 'meta_11_hover_style', 'meta_11_color', 'meta_11_element', 'meta_11_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_11_mixed_width_default', 'fieldset_11_mixed_width_small', 'fieldset_11_mixed_width_medium', 'fieldset_11_mixed_width_large', 'fieldset_11_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_12_settings' => [
      'title' => 'Fieldset 12', 
      'width' => 500, 
      'fields' => [
        'fieldset_12_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_12' => $config->get('fs_switcher.show_text'), 
        'show_meta_12' => $config->get('fs_switcher.show_meta'), 
        'show_image_12' => $config->get('fs_switcher.show_image'), 
        'show_link_12' => $config->get('fs_switcher.show_link'), 
        'fieldset_12_visibility' => $config->get('fs_switcher.visibility'), 
        'text_12_limit' => $config->get('fs_switcher.limit'), 
        'text_12_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_12_style' => $config->get('fs_switcher.style'), 
        'text_12_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_12_color' => $config->get('fs_switcher.color'), 
        'text_12_element' => $config->get('fs_switcher.html_element'), 
        'text_12_margin' => $config->get('fs_switcher.margin'), 
        'meta_12_position' => $config->get('fs_switcher.meta_position'), 
        'meta_12_style' => $config->get('fs_switcher.style'), 
        'meta_12_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_12_color' => $config->get('fs_switcher.color'), 
        'meta_12_element' => $config->get('fs_switcher.html_element'), 
        'meta_12_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_12_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_12_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_12_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_12_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_12_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_12_target', 'show_text_12', 'show_meta_12', 'show_image_12', 'show_link_12', 'fieldset_12_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_12_limit', 'text_12_limit_length', 'text_12_style', 'text_12_hover_style', 'text_12_color', 'text_12_element', 'text_12_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_12_position', 'meta_12_style', 'meta_12_hover_style', 'meta_12_color', 'meta_12_element', 'meta_12_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_12_mixed_width_default', 'fieldset_12_mixed_width_small', 'fieldset_12_mixed_width_medium', 'fieldset_12_mixed_width_large', 'fieldset_12_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_13_settings' => [
      'title' => 'Fieldset 13', 
      'width' => 500, 
      'fields' => [
        'fieldset_13_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_13' => $config->get('fs_switcher.show_text'), 
        'show_meta_13' => $config->get('fs_switcher.show_meta'), 
        'show_image_13' => $config->get('fs_switcher.show_image'), 
        'show_link_13' => $config->get('fs_switcher.show_link'), 
        'fieldset_13_visibility' => $config->get('fs_switcher.visibility'), 
        'text_13_limit' => $config->get('fs_switcher.limit'), 
        'text_13_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_13_style' => $config->get('fs_switcher.style'), 
        'text_13_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_13_color' => $config->get('fs_switcher.color'), 
        'text_13_element' => $config->get('fs_switcher.html_element'), 
        'text_13_margin' => $config->get('fs_switcher.margin'), 
        'meta_13_position' => $config->get('fs_switcher.meta_position'), 
        'meta_13_style' => $config->get('fs_switcher.style'), 
        'meta_13_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_13_color' => $config->get('fs_switcher.color'), 
        'meta_13_element' => $config->get('fs_switcher.html_element'), 
        'meta_13_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_13_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_13_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_13_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_13_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_13_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_13_target', 'show_text_13', 'show_meta_13', 'show_image_13', 'show_link_13', 'fieldset_13_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_13_limit', 'text_13_limit_length', 'text_13_style', 'text_13_hover_style', 'text_13_color', 'text_13_element', 'text_13_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_13_position', 'meta_13_style', 'meta_13_hover_style', 'meta_13_color', 'meta_13_element', 'meta_13_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_13_mixed_width_default', 'fieldset_13_mixed_width_small', 'fieldset_13_mixed_width_medium', 'fieldset_13_mixed_width_large', 'fieldset_13_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_14_settings' => [
      'title' => 'Fieldset 14', 
      'width' => 500, 
      'fields' => [
        'fieldset_14_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_14' => $config->get('fs_switcher.show_text'), 
        'show_meta_14' => $config->get('fs_switcher.show_meta'), 
        'show_image_14' => $config->get('fs_switcher.show_image'), 
        'show_link_14' => $config->get('fs_switcher.show_link'), 
        'fieldset_14_visibility' => $config->get('fs_switcher.visibility'), 
        'text_14_limit' => $config->get('fs_switcher.limit'), 
        'text_14_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_14_style' => $config->get('fs_switcher.style'), 
        'text_14_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_14_color' => $config->get('fs_switcher.color'), 
        'text_14_element' => $config->get('fs_switcher.html_element'), 
        'text_14_margin' => $config->get('fs_switcher.margin'), 
        'meta_14_position' => $config->get('fs_switcher.meta_position'), 
        'meta_14_style' => $config->get('fs_switcher.style'), 
        'meta_14_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_14_color' => $config->get('fs_switcher.color'), 
        'meta_14_element' => $config->get('fs_switcher.html_element'), 
        'meta_14_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_14_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_14_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_14_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_14_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_14_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_14_target', 'show_text_14', 'show_meta_14', 'show_image_14', 'show_link_14', 'fieldset_14_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_14_limit', 'text_14_limit_length', 'text_14_style', 'text_14_hover_style', 'text_14_color', 'text_14_element', 'text_14_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_14_position', 'meta_14_style', 'meta_14_hover_style', 'meta_14_color', 'meta_14_element', 'meta_14_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_14_mixed_width_default', 'fieldset_14_mixed_width_small', 'fieldset_14_mixed_width_medium', 'fieldset_14_mixed_width_large', 'fieldset_14_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_15_settings' => [
      'title' => 'Fieldset 15', 
      'width' => 500, 
      'fields' => [
        'fieldset_15_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_15' => $config->get('fs_switcher.show_text'), 
        'show_meta_15' => $config->get('fs_switcher.show_meta'), 
        'show_image_15' => $config->get('fs_switcher.show_image'), 
        'show_link_15' => $config->get('fs_switcher.show_link'), 
        'fieldset_15_visibility' => $config->get('fs_switcher.visibility'), 
        'text_15_limit' => $config->get('fs_switcher.limit'), 
        'text_15_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_15_style' => $config->get('fs_switcher.style'), 
        'text_15_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_15_color' => $config->get('fs_switcher.color'), 
        'text_15_element' => $config->get('fs_switcher.html_element'), 
        'text_15_margin' => $config->get('fs_switcher.margin'), 
        'meta_15_position' => $config->get('fs_switcher.meta_position'), 
        'meta_15_style' => $config->get('fs_switcher.style'), 
        'meta_15_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_15_color' => $config->get('fs_switcher.color'), 
        'meta_15_element' => $config->get('fs_switcher.html_element'), 
        'meta_15_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_15_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_15_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_15_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_15_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_15_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_15_target', 'show_text_15', 'show_meta_15', 'show_image_15', 'show_link_15', 'fieldset_15_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_15_limit', 'text_15_limit_length', 'text_15_style', 'text_15_hover_style', 'text_15_color', 'text_15_element', 'text_15_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_15_position', 'meta_15_style', 'meta_15_hover_style', 'meta_15_color', 'meta_15_element', 'meta_15_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_15_mixed_width_default', 'fieldset_15_mixed_width_small', 'fieldset_15_mixed_width_medium', 'fieldset_15_mixed_width_large', 'fieldset_15_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_16_settings' => [
      'title' => 'Fieldset 16', 
      'width' => 500, 
      'fields' => [
        'fieldset_16_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_16' => $config->get('fs_switcher.show_text'), 
        'show_meta_16' => $config->get('fs_switcher.show_meta'), 
        'show_image_16' => $config->get('fs_switcher.show_image'), 
        'show_link_16' => $config->get('fs_switcher.show_link'), 
        'fieldset_16_visibility' => $config->get('fs_switcher.visibility'), 
        'text_16_limit' => $config->get('fs_switcher.limit'), 
        'text_16_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_16_style' => $config->get('fs_switcher.style'), 
        'text_16_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_16_color' => $config->get('fs_switcher.color'), 
        'text_16_element' => $config->get('fs_switcher.html_element'), 
        'text_16_margin' => $config->get('fs_switcher.margin'), 
        'meta_16_position' => $config->get('fs_switcher.meta_position'), 
        'meta_16_style' => $config->get('fs_switcher.style'), 
        'meta_16_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_16_color' => $config->get('fs_switcher.color'), 
        'meta_16_element' => $config->get('fs_switcher.html_element'), 
        'meta_16_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_16_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_16_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_16_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_16_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_16_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_16_target', 'show_text_16', 'show_meta_16', 'show_image_16', 'show_link_16', 'fieldset_16_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_16_limit', 'text_16_limit_length', 'text_16_style', 'text_16_hover_style', 'text_16_color', 'text_16_element', 'text_16_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_16_position', 'meta_16_style', 'meta_16_hover_style', 'meta_16_color', 'meta_16_element', 'meta_16_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_16_mixed_width_default', 'fieldset_16_mixed_width_small', 'fieldset_16_mixed_width_medium', 'fieldset_16_mixed_width_large', 'fieldset_16_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_17_settings' => [
      'title' => 'Fieldset 17', 
      'width' => 500, 
      'fields' => [
        'fieldset_17_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_17' => $config->get('fs_switcher.show_text'), 
        'show_meta_17' => $config->get('fs_switcher.show_meta'), 
        'show_image_17' => $config->get('fs_switcher.show_image'), 
        'show_link_17' => $config->get('fs_switcher.show_link'), 
        'fieldset_17_visibility' => $config->get('fs_switcher.visibility'), 
        'text_17_limit' => $config->get('fs_switcher.limit'), 
        'text_17_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_17_style' => $config->get('fs_switcher.style'), 
        'text_17_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_17_color' => $config->get('fs_switcher.color'), 
        'text_17_element' => $config->get('fs_switcher.html_element'), 
        'text_17_margin' => $config->get('fs_switcher.margin'), 
        'meta_17_position' => $config->get('fs_switcher.meta_position'), 
        'meta_17_style' => $config->get('fs_switcher.style'), 
        'meta_17_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_17_color' => $config->get('fs_switcher.color'), 
        'meta_17_element' => $config->get('fs_switcher.html_element'), 
        'meta_17_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_17_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_17_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_17_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_17_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_17_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_17_target', 'show_text_17', 'show_meta_17', 'show_image_17', 'show_link_17', 'fieldset_17_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_17_limit', 'text_17_limit_length', 'text_17_style', 'text_17_hover_style', 'text_17_color', 'text_17_element', 'text_17_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_17_position', 'meta_17_style', 'meta_17_hover_style', 'meta_17_color', 'meta_17_element', 'meta_17_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_17_mixed_width_default', 'fieldset_17_mixed_width_small', 'fieldset_17_mixed_width_medium', 'fieldset_17_mixed_width_large', 'fieldset_17_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_18_settings' => [
      'title' => 'Fieldset 18', 
      'width' => 500, 
      'fields' => [
        'fieldset_18_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_18' => $config->get('fs_switcher.show_text'), 
        'show_meta_18' => $config->get('fs_switcher.show_meta'), 
        'show_image_18' => $config->get('fs_switcher.show_image'), 
        'show_link_18' => $config->get('fs_switcher.show_link'), 
        'fieldset_18_visibility' => $config->get('fs_switcher.visibility'), 
        'text_18_limit' => $config->get('fs_switcher.limit'), 
        'text_18_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_18_style' => $config->get('fs_switcher.style'), 
        'text_18_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_18_color' => $config->get('fs_switcher.color'), 
        'text_18_element' => $config->get('fs_switcher.html_element'), 
        'text_18_margin' => $config->get('fs_switcher.margin'), 
        'meta_18_position' => $config->get('fs_switcher.meta_position'), 
        'meta_18_style' => $config->get('fs_switcher.style'), 
        'meta_18_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_18_color' => $config->get('fs_switcher.color'), 
        'meta_18_element' => $config->get('fs_switcher.html_element'), 
        'meta_18_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_18_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_18_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_18_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_18_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_18_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_18_target', 'show_text_18', 'show_meta_18', 'show_image_18', 'show_link_18', 'fieldset_18_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_18_limit', 'text_18_limit_length', 'text_18_style', 'text_18_hover_style', 'text_18_color', 'text_18_element', 'text_18_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_18_position', 'meta_18_style', 'meta_18_hover_style', 'meta_18_color', 'meta_18_element', 'meta_18_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_18_mixed_width_default', 'fieldset_18_mixed_width_small', 'fieldset_18_mixed_width_medium', 'fieldset_18_mixed_width_large', 'fieldset_18_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_19_settings' => [
      'title' => 'Fieldset 19', 
      'width' => 500, 
      'fields' => [
        'fieldset_19_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_19' => $config->get('fs_switcher.show_text'), 
        'show_meta_19' => $config->get('fs_switcher.show_meta'), 
        'show_image_19' => $config->get('fs_switcher.show_image'), 
        'show_link_19' => $config->get('fs_switcher.show_link'), 
        'fieldset_19_visibility' => $config->get('fs_switcher.visibility'), 
        'text_19_limit' => $config->get('fs_switcher.limit'), 
        'text_19_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_19_style' => $config->get('fs_switcher.style'), 
        'text_19_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_19_color' => $config->get('fs_switcher.color'), 
        'text_19_element' => $config->get('fs_switcher.html_element'), 
        'text_19_margin' => $config->get('fs_switcher.margin'), 
        'meta_19_position' => $config->get('fs_switcher.meta_position'), 
        'meta_19_style' => $config->get('fs_switcher.style'), 
        'meta_19_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_19_color' => $config->get('fs_switcher.color'), 
        'meta_19_element' => $config->get('fs_switcher.html_element'), 
        'meta_19_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_19_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_19_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_19_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_19_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_19_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_19_target', 'show_text_19', 'show_meta_19', 'show_image_19', 'show_link_19', 'fieldset_19_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_19_limit', 'text_19_limit_length', 'text_19_style', 'text_19_hover_style', 'text_19_color', 'text_19_element', 'text_19_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_19_position', 'meta_19_style', 'meta_19_hover_style', 'meta_19_color', 'meta_19_element', 'meta_19_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_19_mixed_width_default', 'fieldset_19_mixed_width_small', 'fieldset_19_mixed_width_medium', 'fieldset_19_mixed_width_large', 'fieldset_19_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_fieldset_20_settings' => [
      'title' => 'Fieldset 20', 
      'width' => 500, 
      'fields' => [
        'fieldset_20_target' => $config->get('fs_switcher.target_grid'), 
        'show_text_20' => $config->get('fs_switcher.show_text'), 
        'show_meta_20' => $config->get('fs_switcher.show_meta'), 
        'show_image_20' => $config->get('fs_switcher.show_image'), 
        'show_link_20' => $config->get('fs_switcher.show_link'), 
        'fieldset_20_visibility' => $config->get('fs_switcher.visibility'), 
        'text_20_limit' => $config->get('fs_switcher.limit'), 
        'text_20_limit_length' => $config->get('fs_switcher.limit_length'), 
        'text_20_style' => $config->get('fs_switcher.style'), 
        'text_20_hover_style' => $config->get('fs_switcher.hover_style'), 
        'text_20_color' => $config->get('fs_switcher.color'), 
        'text_20_element' => $config->get('fs_switcher.html_element'), 
        'text_20_margin' => $config->get('fs_switcher.margin'), 
        'meta_20_position' => $config->get('fs_switcher.meta_position'), 
        'meta_20_style' => $config->get('fs_switcher.style'), 
        'meta_20_hover_style' => $config->get('fs_switcher.hover_style'), 
        'meta_20_color' => $config->get('fs_switcher.color'), 
        'meta_20_element' => $config->get('fs_switcher.html_element'), 
        'meta_20_margin' => $config->get('fs_switcher.margin'), 
        'fieldset_20_mixed_width_default' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_20_mixed_width_small' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_20_mixed_width_medium' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_20_mixed_width_large' => $config->get('fs_switcher.grid_columns'), 
        'fieldset_20_mixed_width_xlarge' => $config->get('fs_switcher.grid_columns')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Display', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_20_target', 'show_text_20', 'show_meta_20', 'show_image_20', 'show_link_20', 'fieldset_20_visibility']
            ], [
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['text_20_limit', 'text_20_limit_length', 'text_20_style', 'text_20_hover_style', 'text_20_color', 'text_20_element', 'text_20_margin']
            ], [
              'label' => 'Meta', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['meta_20_position', 'meta_20_style', 'meta_20_hover_style', 'meta_20_color', 'meta_20_element', 'meta_20_margin']
            ], [
              'label' => 'Mixed Width', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['fieldset_20_mixed_width_default', 'fieldset_20_mixed_width_small', 'fieldset_20_mixed_width_medium', 'fieldset_20_mixed_width_large', 'fieldset_20_mixed_width_xlarge']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_grid_1_settings' => [
      'title' => 'Grid 1', 
      'width' => 500, 
      'fields' => [
        'grid_1_position' => $config->get('fs_switcher.grid_position'), 
        'grid_1_column_gap' => $config->get('fs_switcher.grid_column_gap'), 
        'grid_1_row_gap' => $config->get('fs_switcher.grid_row_gap'), 
        'grid_1_divider_top' => $config->get('fs_switcher.grid_divider_top'), 
        'grid_1_divider' => $config->get('fs_switcher.grid_divider'), 
        'grid_1_column_align' => $config->get('fs_switcher.grid_column_align'), 
        'grid_1_row_align' => $config->get('fs_switcher.grid_row_align'), 
        'grid_1_text_align' => $config->get('fs_switcher.grid_text_align'), 
        'grid_1_text_align_breakpoint' => $config->get('fs_switcher.grid_breakpoint'), 
        'grid_1_text_align_fallback' => $config->get('fs_switcher.grid_text_align_fallback'), 
        'grid_1_slider' => $config->get('fs_switcher.grid_slider'), 
        '_grid_1_slider_panel' => $config->get('fs_switcher.grid_slider_panel'), 
        'grid_1_margin' => $config->get('fs_switcher.grid_margin'), 
        'grid_1_visibility' => $config->get('fs_switcher.visibility'), 
        'grid_1_default' => $config->get('fs_switcher.grid_columns'), 
        'grid_1_small' => $config->get('fs_switcher.grid_columns'), 
        'grid_1_medium' => $config->get('fs_switcher.grid_columns'), 
        'grid_1_large' => $config->get('fs_switcher.grid_columns'), 
        'grid_1_xlarge' => $config->get('fs_switcher.grid_columns'), 
        'grid_1_panel_style' => $config->get('fs_switcher.panel_style'), 
        'grid_1_panel_card_offset' => $config->get('fs_switcher.panel_card_offset'), 
        'grid_1_panel_padding' => $config->get('fs_switcher.panel_padding'), 
        'grid_1_image_width' => $config->get('fs_switcher.image_width'), 
        'grid_1_image_height' => $config->get('fs_switcher.image_height'), 
        'grid_1_icon_width' => $config->get('fs_switcher.icon_width'), 
        'grid_1_image_loading' => $config->get('fs_switcher.image_loading'), 
        'grid_1_image_fetchpriority' => $config->get('fs_switcher.image_fetchpriority'), 
        'grid_1_image_decoding' => $config->get('fs_switcher.image_decoding'), 
        'grid_1_image_cache_disable' => $config->get('fs_switcher.cache_disable'), 
        'grid_1_image_border' => $config->get('fs_switcher.image_border'), 
        'grid_1_icon_color' => $config->get('fs_switcher.icon_color'), 
        'grid_1_image_align' => $config->get('fs_switcher.image_align'), 
        'grid_1_image_grid_width' => $config->get('fs_switcher.grid_width'), 
        'grid_1_image_grid_column_gap' => $config->get('fs_switcher.grid_column_gap'), 
        'grid_1_image_grid_row_gap' => $config->get('fs_switcher.grid_row_gap'), 
        'grid_1_image_grid_breakpoint' => $config->get('fs_switcher.grid_breakpoint'), 
        'grid_1_image_vertical_align' => $config->get('fs_switcher.image_vertical_align'), 
        'grid_1_image_svg_inline' => $config->get('fs_switcher.image_svg_inline'), 
        'grid_1_image_svg_animate' => $config->get('fs_switcher.image_svg_animate'), 
        'grid_1_image_svg_color' => $config->get('fs_switcher.image_svg_color'), 
        'grid_1_image_text_color' => $config->get('fs_switcher.text_color'), 
        'grid_1_image_margin_bottom' => $config->get('fs_switcher.image_margin_bottom'), 
        'grid_1_image_margin_top' => $config->get('fs_switcher.image_margin_top')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Grid', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['grid_1_position', 'grid_1_column_gap', 'grid_1_row_gap', 'grid_1_divider_top', 'grid_1_divider', 'grid_1_column_align', 'grid_1_row_align', 'grid_1_text_align', 'grid_1_text_align_breakpoint', 'grid_1_text_align_fallback', 'grid_1_slider', '_grid_1_slider_panel', 'grid_1_margin', 'grid_1_visibility']
            ], [
              'label' => 'Columns', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['grid_1_default', 'grid_1_small', 'grid_1_medium', 'grid_1_large', 'grid_1_xlarge']
            ], [
              'label' => 'Panel', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['grid_1_panel_style', 'grid_1_panel_card_offset', 'grid_1_panel_padding']
            ], [
              'label' => 'Image', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => [[
                  'label' => 'Width/Height/Icon', 
                  'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.', 
                  'type' => 'grid', 
                  'width' => '1-3', 
                  'fields' => ['grid_1_image_width', 'grid_1_image_height', 'grid_1_icon_width']
                ], 'grid_1_image_loading', 'grid_1_image_fetchpriority', 'grid_1_image_decoding', 'grid_1_image_cache_disable', 'grid_1_image_border', 'grid_1_icon_color', 'grid_1_image_align', 'grid_1_image_grid_width', 'grid_1_image_grid_column_gap', 'grid_1_image_grid_row_gap', 'grid_1_image_grid_breakpoint', 'grid_1_image_vertical_align', 'grid_1_image_svg_inline', 'grid_1_image_svg_animate', 'grid_1_image_svg_color', 'grid_1_image_text_color', 'grid_1_image_margin_top', 'grid_1_image_margin_bottom']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_grid_2_settings' => [
      'title' => 'Grid 2', 
      'width' => 500, 
      'fields' => [
        'grid_2_position' => $config->get('fs_switcher.grid_position'), 
        'grid_2_column_gap' => $config->get('fs_switcher.grid_column_gap'), 
        'grid_2_row_gap' => $config->get('fs_switcher.grid_row_gap'), 
        'grid_2_divider_top' => $config->get('fs_switcher.grid_divider_top'), 
        'grid_2_divider' => $config->get('fs_switcher.grid_divider'), 
        'grid_2_column_align' => $config->get('fs_switcher.grid_column_align'), 
        'grid_2_row_align' => $config->get('fs_switcher.grid_row_align'), 
        'grid_2_text_align' => $config->get('fs_switcher.grid_text_align'), 
        'grid_2_text_align_breakpoint' => $config->get('fs_switcher.grid_breakpoint'), 
        'grid_2_text_align_fallback' => $config->get('fs_switcher.grid_text_align_fallback'), 
        'grid_2_slider' => $config->get('fs_switcher.grid_slider'), 
        '_grid_2_slider_panel' => $config->get('fs_switcher.grid_slider_panel'), 
        'grid_2_margin' => $config->get('fs_switcher.grid_margin'), 
        'grid_2_visibility' => $config->get('fs_switcher.visibility'), 
        'grid_2_default' => $config->get('fs_switcher.grid_columns'), 
        'grid_2_small' => $config->get('fs_switcher.grid_columns'), 
        'grid_2_medium' => $config->get('fs_switcher.grid_columns'), 
        'grid_2_large' => $config->get('fs_switcher.grid_columns'), 
        'grid_2_xlarge' => $config->get('fs_switcher.grid_columns'), 
        'grid_2_panel_style' => $config->get('fs_switcher.panel_style'), 
        'grid_2_panel_card_offset' => $config->get('fs_switcher.panel_card_offset'), 
        'grid_2_panel_padding' => $config->get('fs_switcher.panel_padding'), 
        'grid_2_image_width' => $config->get('fs_switcher.image_width'), 
        'grid_2_image_height' => $config->get('fs_switcher.image_height'), 
        'grid_2_icon_width' => $config->get('fs_switcher.icon_width'), 
        'grid_2_image_loading' => $config->get('fs_switcher.image_loading'), 
        'grid_2_image_fetchpriority' => $config->get('fs_switcher.image_fetchpriority'), 
        'grid_2_image_decoding' => $config->get('fs_switcher.image_decoding'), 
        'grid_2_image_cache_disable' => $config->get('fs_switcher.cache_disable'), 
        'grid_2_image_border' => $config->get('fs_switcher.image_border'), 
        'grid_2_icon_color' => $config->get('fs_switcher.icon_color'), 
        'grid_2_image_align' => $config->get('fs_switcher.image_align'), 
        'grid_2_image_grid_width' => $config->get('fs_switcher.grid_width'), 
        'grid_2_image_grid_column_gap' => $config->get('fs_switcher.grid_column_gap'), 
        'grid_2_image_grid_row_gap' => $config->get('fs_switcher.grid_row_gap'), 
        'grid_2_image_grid_breakpoint' => $config->get('fs_switcher.grid_breakpoint'), 
        'grid_2_image_vertical_align' => $config->get('fs_switcher.image_vertical_align'), 
        'grid_2_image_svg_inline' => $config->get('fs_switcher.image_svg_inline'), 
        'grid_2_image_svg_animate' => $config->get('fs_switcher.image_svg_animate'), 
        'grid_2_image_svg_color' => $config->get('fs_switcher.image_svg_color'), 
        'grid_2_image_text_color' => $config->get('fs_switcher.text_color'), 
        'grid_2_image_margin_bottom' => $config->get('fs_switcher.image_margin_bottom'), 
        'grid_2_image_margin_top' => $config->get('fs_switcher.image_margin_top')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Grid', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['grid_2_position', 'grid_2_column_gap', 'grid_2_row_gap', 'grid_2_divider_top', 'grid_2_divider', 'grid_2_column_align', 'grid_2_row_align', 'grid_2_text_align', 'grid_2_text_align_breakpoint', 'grid_2_text_align_fallback', 'grid_2_slider', '_grid_2_slider_panel', 'grid_2_margin', 'grid_2_visibility']
            ], [
              'label' => 'Columns', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['grid_2_default', 'grid_2_small', 'grid_2_medium', 'grid_2_large', 'grid_2_xlarge']
            ], [
              'label' => 'Panel', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['grid_2_panel_style', 'grid_2_panel_card_offset', 'grid_2_panel_padding']
            ], [
              'label' => 'Image', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => [[
                  'label' => 'Width/Height', 
                  'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.', 
                  'type' => 'grid', 
                  'width' => '1-3', 
                  'fields' => ['grid_2_image_width', 'grid_2_image_height', 'grid_2_icon_width']
                ], 'grid_2_image_loading', 'grid_2_image_fetchpriority', 'grid_2_image_decoding', 'grid_2_image_cache_disable', 'grid_2_image_border', 'grid_2_icon_color', 'grid_2_image_align', 'grid_2_image_grid_width', 'grid_2_image_grid_column_gap', 'grid_2_image_grid_row_gap', 'grid_2_image_grid_breakpoint', 'grid_2_image_vertical_align', 'grid_2_image_svg_inline', 'grid_2_image_svg_animate', 'grid_2_image_svg_color', 'grid_2_image_text_color', 'grid_2_image_margin_top', 'grid_2_image_margin_bottom']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_grid_3_settings' => [
      'title' => 'Grid 3', 
      'width' => 500, 
      'fields' => [
        'grid_3_position' => $config->get('fs_switcher.grid_position'), 
        'grid_3_column_gap' => $config->get('fs_switcher.grid_column_gap'), 
        'grid_3_row_gap' => $config->get('fs_switcher.grid_row_gap'), 
        'grid_3_divider_top' => $config->get('fs_switcher.grid_divider_top'), 
        'grid_3_divider' => $config->get('fs_switcher.grid_divider'), 
        'grid_3_column_align' => $config->get('fs_switcher.grid_column_align'), 
        'grid_3_row_align' => $config->get('fs_switcher.grid_row_align'), 
        'grid_3_text_align' => $config->get('fs_switcher.grid_text_align'), 
        'grid_3_text_align_breakpoint' => $config->get('fs_switcher.grid_breakpoint'), 
        'grid_3_text_align_fallback' => $config->get('fs_switcher.grid_text_align_fallback'), 
        'grid_3_slider' => $config->get('fs_switcher.grid_slider'), 
        '_grid_3_slider_panel' => $config->get('fs_switcher.grid_slider_panel'), 
        'grid_3_margin' => $config->get('fs_switcher.grid_margin'), 
        'grid_3_visibility' => $config->get('fs_switcher.visibility'), 
        'grid_3_default' => $config->get('fs_switcher.grid_columns'), 
        'grid_3_small' => $config->get('fs_switcher.grid_columns'), 
        'grid_3_medium' => $config->get('fs_switcher.grid_columns'), 
        'grid_3_large' => $config->get('fs_switcher.grid_columns'), 
        'grid_3_xlarge' => $config->get('fs_switcher.grid_columns'), 
        'grid_3_panel_style' => $config->get('fs_switcher.panel_style'), 
        'grid_3_panel_card_offset' => $config->get('fs_switcher.panel_card_offset'), 
        'grid_3_panel_padding' => $config->get('fs_switcher.panel_padding'), 
        'grid_3_image_width' => $config->get('fs_switcher.image_width'), 
        'grid_3_image_height' => $config->get('fs_switcher.image_height'), 
        'grid_3_icon_width' => $config->get('fs_switcher.icon_width'), 
        'grid_3_image_loading' => $config->get('fs_switcher.image_loading'), 
        'grid_3_image_fetchpriority' => $config->get('fs_switcher.image_fetchpriority'), 
        'grid_3_image_decoding' => $config->get('fs_switcher.image_decoding'), 
        'grid_3_image_cache_disable' => $config->get('fs_switcher.cache_disable'), 
        'grid_3_image_border' => $config->get('fs_switcher.image_border'), 
        'grid_3_icon_color' => $config->get('fs_switcher.icon_color'), 
        'grid_3_image_align' => $config->get('fs_switcher.image_align'), 
        'grid_3_image_grid_width' => $config->get('fs_switcher.grid_width'), 
        'grid_3_image_grid_column_gap' => $config->get('fs_switcher.grid_column_gap'), 
        'grid_3_image_grid_row_gap' => $config->get('fs_switcher.grid_row_gap'), 
        'grid_3_image_grid_breakpoint' => $config->get('fs_switcher.grid_breakpoint'), 
        'grid_3_image_vertical_align' => $config->get('fs_switcher.image_vertical_align'), 
        'grid_3_image_svg_inline' => $config->get('fs_switcher.image_svg_inline'), 
        'grid_3_image_svg_animate' => $config->get('fs_switcher.image_svg_animate'), 
        'grid_3_image_svg_color' => $config->get('fs_switcher.image_svg_color'), 
        'grid_3_image_text_color' => $config->get('fs_switcher.text_color'), 
        'grid_3_image_margin_bottom' => $config->get('fs_switcher.image_margin_bottom'), 
        'grid_3_image_margin_top' => $config->get('fs_switcher.image_margin_top')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Grid', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['grid_3_position', 'grid_3_column_gap', 'grid_3_row_gap', 'grid_3_divider_top', 'grid_3_divider', 'grid_3_column_align', 'grid_3_row_align', 'grid_3_text_align', 'grid_3_text_align_breakpoint', 'grid_3_text_align_fallback', 'grid_3_slider', '_grid_3_slider_panel', 'grid_3_margin', 'grid_3_visibility']
            ], [
              'label' => 'Columns', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['grid_3_default', 'grid_3_small', 'grid_3_medium', 'grid_3_large', 'grid_3_xlarge']
            ], [
              'label' => 'Panel', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['grid_3_panel_style', 'grid_3_panel_card_offset', 'grid_3_panel_padding']
            ], [
              'label' => 'Image', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => [[
                  'label' => 'Width/Height', 
                  'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.', 
                  'type' => 'grid', 
                  'width' => '1-3', 
                  'fields' => ['grid_3_image_width', 'grid_3_image_height', 'grid_3_icon_width']
                ], 'grid_3_image_loading', 'grid_3_image_fetchpriority', 'grid_3_image_decoding', 'grid_3_image_cache_disable', 'grid_3_image_border', 'grid_3_icon_color', 'grid_3_image_align', 'grid_3_image_grid_width', 'grid_3_image_grid_column_gap', 'grid_3_image_grid_row_gap', 'grid_3_image_grid_breakpoint', 'grid_3_image_vertical_align', 'grid_3_image_svg_inline', 'grid_3_image_svg_animate', 'grid_3_image_svg_color', 'grid_3_image_text_color', 'grid_3_image_margin_top', 'grid_3_image_margin_bottom']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_grid_4_settings' => [
      'title' => 'Grid 4', 
      'width' => 500, 
      'fields' => [
        'grid_4_position' => $config->get('fs_switcher.grid_position'), 
        'grid_4_column_gap' => $config->get('fs_switcher.grid_column_gap'), 
        'grid_4_row_gap' => $config->get('fs_switcher.grid_row_gap'), 
        'grid_4_divider_top' => $config->get('fs_switcher.grid_divider_top'), 
        'grid_4_divider' => $config->get('fs_switcher.grid_divider'), 
        'grid_4_column_align' => $config->get('fs_switcher.grid_column_align'), 
        'grid_4_row_align' => $config->get('fs_switcher.grid_row_align'), 
        'grid_4_text_align' => $config->get('fs_switcher.grid_text_align'), 
        'grid_4_text_align_breakpoint' => $config->get('fs_switcher.grid_breakpoint'), 
        'grid_4_text_align_fallback' => $config->get('fs_switcher.grid_text_align_fallback'), 
        'grid_4_slider' => $config->get('fs_switcher.grid_slider'), 
        '_grid_4_slider_panel' => $config->get('fs_switcher.grid_slider_panel'), 
        'grid_4_margin' => $config->get('fs_switcher.grid_margin'), 
        'grid_4_visibility' => $config->get('fs_switcher.visibility'), 
        'grid_4_default' => $config->get('fs_switcher.grid_columns'), 
        'grid_4_small' => $config->get('fs_switcher.grid_columns'), 
        'grid_4_medium' => $config->get('fs_switcher.grid_columns'), 
        'grid_4_large' => $config->get('fs_switcher.grid_columns'), 
        'grid_4_xlarge' => $config->get('fs_switcher.grid_columns'), 
        'grid_4_panel_style' => $config->get('fs_switcher.panel_style'), 
        'grid_4_panel_card_offset' => $config->get('fs_switcher.panel_card_offset'), 
        'grid_4_panel_padding' => $config->get('fs_switcher.panel_padding'), 
        'grid_4_image_width' => $config->get('fs_switcher.image_width'), 
        'grid_4_image_height' => $config->get('fs_switcher.image_height'), 
        'grid_4_icon_width' => $config->get('fs_switcher.icon_width'), 
        'grid_4_image_loading' => $config->get('fs_switcher.image_loading'), 
        'grid_4_image_fetchpriority' => $config->get('fs_switcher.image_fetchpriority'), 
        'grid_4_image_decoding' => $config->get('fs_switcher.image_decoding'), 
        'grid_4_image_cache_disable' => $config->get('fs_switcher.cache_disable'), 
        'grid_4_image_border' => $config->get('fs_switcher.image_border'), 
        'grid_4_icon_color' => $config->get('fs_switcher.icon_color'), 
        'grid_4_image_align' => $config->get('fs_switcher.image_align'), 
        'grid_4_image_grid_width' => $config->get('fs_switcher.grid_width'), 
        'grid_4_image_grid_column_gap' => $config->get('fs_switcher.grid_column_gap'), 
        'grid_4_image_grid_row_gap' => $config->get('fs_switcher.grid_row_gap'), 
        'grid_4_image_grid_breakpoint' => $config->get('fs_switcher.grid_breakpoint'), 
        'grid_4_image_vertical_align' => $config->get('fs_switcher.image_vertical_align'), 
        'grid_4_image_svg_inline' => $config->get('fs_switcher.image_svg_inline'), 
        'grid_4_image_svg_animate' => $config->get('fs_switcher.image_svg_animate'), 
        'grid_4_image_svg_color' => $config->get('fs_switcher.image_svg_color'), 
        'grid_4_image_text_color' => $config->get('fs_switcher.text_color'), 
        'grid_4_image_margin_bottom' => $config->get('fs_switcher.image_margin_bottom'), 
        'grid_4_image_margin_top' => $config->get('fs_switcher.image_margin_top')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Grid', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['grid_4_position', 'grid_4_column_gap', 'grid_4_row_gap', 'grid_4_divider_top', 'grid_4_divider', 'grid_4_column_align', 'grid_4_row_align', 'grid_4_text_align', 'grid_4_text_align_breakpoint', 'grid_4_text_align_fallback', 'grid_4_slider', '_grid_4_slider_panel', 'grid_4_margin', 'grid_4_visibility']
            ], [
              'label' => 'Columns', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['grid_4_default', 'grid_4_small', 'grid_4_medium', 'grid_4_large', 'grid_4_xlarge']
            ], [
              'label' => 'Panel', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['grid_4_panel_style', 'grid_4_panel_card_offset', 'grid_4_panel_padding']
            ], [
              'label' => 'Image', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => [[
                  'label' => 'Width/Height', 
                  'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.', 
                  'type' => 'grid', 
                  'width' => '1-3', 
                  'fields' => ['grid_4_image_width', 'grid_4_image_height', 'grid_4_icon_width']
                ], 'grid_4_image_loading', 'grid_4_image_fetchpriority', 'grid_4_image_decoding', 'grid_4_image_cache_disable', 'grid_4_image_border', 'grid_4_icon_color', 'grid_4_image_align', 'grid_4_image_grid_width', 'grid_4_image_grid_column_gap', 'grid_4_image_grid_row_gap', 'grid_4_image_grid_breakpoint', 'grid_4_image_vertical_align', 'grid_4_image_svg_inline', 'grid_4_image_svg_animate', 'grid_4_image_svg_color', 'grid_4_image_text_color', 'grid_4_image_margin_top', 'grid_4_image_margin_bottom']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_grid_5_settings' => [
      'title' => 'Grid 5', 
      'width' => 500, 
      'fields' => [
        'grid_5_position' => $config->get('fs_switcher.grid_position'), 
        'grid_5_column_gap' => $config->get('fs_switcher.grid_column_gap'), 
        'grid_5_row_gap' => $config->get('fs_switcher.grid_row_gap'), 
        'grid_5_divider_top' => $config->get('fs_switcher.grid_divider_top'), 
        'grid_5_divider' => $config->get('fs_switcher.grid_divider'), 
        'grid_5_column_align' => $config->get('fs_switcher.grid_column_align'), 
        'grid_5_row_align' => $config->get('fs_switcher.grid_row_align'), 
        'grid_5_text_align' => $config->get('fs_switcher.grid_text_align'), 
        'grid_5_text_align_breakpoint' => $config->get('fs_switcher.grid_breakpoint'), 
        'grid_5_text_align_fallback' => $config->get('fs_switcher.grid_text_align_fallback'), 
        'grid_5_slider' => $config->get('fs_switcher.grid_slider'), 
        '_grid_5_slider_panel' => $config->get('fs_switcher.grid_slider_panel'), 
        'grid_5_margin' => $config->get('fs_switcher.grid_margin'), 
        'grid_5_visibility' => $config->get('fs_switcher.visibility'), 
        'grid_5_default' => $config->get('fs_switcher.grid_columns'), 
        'grid_5_small' => $config->get('fs_switcher.grid_columns'), 
        'grid_5_medium' => $config->get('fs_switcher.grid_columns'), 
        'grid_5_large' => $config->get('fs_switcher.grid_columns'), 
        'grid_5_xlarge' => $config->get('fs_switcher.grid_columns'), 
        'grid_5_panel_style' => $config->get('fs_switcher.panel_style'), 
        'grid_5_panel_card_offset' => $config->get('fs_switcher.panel_card_offset'), 
        'grid_5_panel_padding' => $config->get('fs_switcher.panel_padding'), 
        'grid_5_image_width' => $config->get('fs_switcher.image_width'), 
        'grid_5_image_height' => $config->get('fs_switcher.image_height'), 
        'grid_5_icon_width' => $config->get('fs_switcher.icon_width'), 
        'grid_5_image_loading' => $config->get('fs_switcher.image_loading'), 
        'grid_5_image_fetchpriority' => $config->get('fs_switcher.image_fetchpriority'), 
        'grid_5_image_decoding' => $config->get('fs_switcher.image_decoding'), 
        'grid_5_image_cache_disable' => $config->get('fs_switcher.cache_disable'), 
        'grid_5_image_border' => $config->get('fs_switcher.image_border'), 
        'grid_5_icon_color' => $config->get('fs_switcher.icon_color'), 
        'grid_5_image_align' => $config->get('fs_switcher.image_align'), 
        'grid_5_image_grid_width' => $config->get('fs_switcher.grid_width'), 
        'grid_5_image_grid_column_gap' => $config->get('fs_switcher.grid_column_gap'), 
        'grid_5_image_grid_row_gap' => $config->get('fs_switcher.grid_row_gap'), 
        'grid_5_image_grid_breakpoint' => $config->get('fs_switcher.grid_breakpoint'), 
        'grid_5_image_vertical_align' => $config->get('fs_switcher.image_vertical_align'), 
        'grid_5_image_svg_inline' => $config->get('fs_switcher.image_svg_inline'), 
        'grid_5_image_svg_animate' => $config->get('fs_switcher.image_svg_animate'), 
        'grid_5_image_svg_color' => $config->get('fs_switcher.image_svg_color'), 
        'grid_5_image_text_color' => $config->get('fs_switcher.text_color'), 
        'grid_5_image_margin_bottom' => $config->get('fs_switcher.image_margin_bottom'), 
        'grid_5_image_margin_top' => $config->get('fs_switcher.image_margin_top')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Grid', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['grid_5_position', 'grid_5_column_gap', 'grid_5_row_gap', 'grid_5_divider_top', 'grid_5_divider', 'grid_5_column_align', 'grid_5_row_align', 'grid_5_text_align', 'grid_5_text_align_breakpoint', 'grid_5_text_align_fallback', 'grid_5_slider', '_grid_5_slider_panel', 'grid_5_margin', 'grid_5_visibility']
            ], [
              'label' => 'Columns', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['grid_5_default', 'grid_5_small', 'grid_5_medium', 'grid_5_large', 'grid_5_xlarge']
            ], [
              'label' => 'Panel', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['grid_5_panel_style', 'grid_5_panel_card_offset', 'grid_5_panel_padding']
            ], [
              'label' => 'Image', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => [[
                  'label' => 'Width/Height', 
                  'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.', 
                  'type' => 'grid', 
                  'width' => '1-3', 
                  'fields' => ['grid_5_image_width', 'grid_5_image_height', 'grid_5_icon_width']
                ], 'grid_5_image_loading', 'grid_5_image_fetchpriority', 'grid_5_image_decoding', 'grid_5_image_cache_disable', 'grid_5_image_border', 'grid_5_icon_color', 'grid_5_image_align', 'grid_5_image_grid_width', 'grid_5_image_grid_column_gap', 'grid_5_image_grid_row_gap', 'grid_5_image_grid_breakpoint', 'grid_5_image_vertical_align', 'grid_5_image_svg_inline', 'grid_5_image_svg_animate', 'grid_5_image_svg_color', 'grid_5_image_text_color', 'grid_5_image_margin_top', 'grid_5_image_margin_bottom']
            ]]
        ]
      ]
    ], 
    '_fs_switcher_pro_grid_6_settings' => [
      'title' => 'Grid 6', 
      'width' => 500, 
      'fields' => [
        'grid_6_position' => $config->get('fs_switcher.grid_position'), 
        'grid_6_column_gap' => $config->get('fs_switcher.grid_column_gap'), 
        'grid_6_row_gap' => $config->get('fs_switcher.grid_row_gap'), 
        'grid_6_divider_top' => $config->get('fs_switcher.grid_divider_top'), 
        'grid_6_divider' => $config->get('fs_switcher.grid_divider'), 
        'grid_6_column_align' => $config->get('fs_switcher.grid_column_align'), 
        'grid_6_row_align' => $config->get('fs_switcher.grid_row_align'), 
        'grid_6_text_align' => $config->get('fs_switcher.grid_text_align'), 
        'grid_6_text_align_breakpoint' => $config->get('fs_switcher.grid_breakpoint'), 
        'grid_6_text_align_fallback' => $config->get('fs_switcher.grid_text_align_fallback'), 
        'grid_6_xlarge' => $config->get('fs_switcher.grid_columns'), 
        'grid_6_slider' => $config->get('fs_switcher.grid_slider'), 
        'grid_6_margin' => $config->get('fs_switcher.grid_margin'), 
        'grid_6_visibility' => $config->get('fs_switcher.visibility'), 
        'grid_6_default' => $config->get('fs_switcher.grid_columns'), 
        'grid_6_small' => $config->get('fs_switcher.grid_columns'), 
        'grid_6_medium' => $config->get('fs_switcher.grid_columns'), 
        'grid_6_large' => $config->get('fs_switcher.grid_columns'), 
        '_grid_6_slider_panel' => $config->get('fs_switcher.grid_slider_panel'), 
        'grid_6_panel_style' => $config->get('fs_switcher.panel_style'), 
        'grid_6_panel_card_offset' => $config->get('fs_switcher.panel_card_offset'), 
        'grid_6_panel_padding' => $config->get('fs_switcher.panel_padding'), 
        'grid_6_image_width' => $config->get('fs_switcher.image_width'), 
        'grid_6_image_height' => $config->get('fs_switcher.image_height'), 
        'grid_6_icon_width' => $config->get('fs_switcher.icon_width'), 
        'grid_6_image_loading' => $config->get('fs_switcher.image_loading'), 
        'grid_6_image_fetchpriority' => $config->get('fs_switcher.image_fetchpriority'), 
        'grid_6_image_decoding' => $config->get('fs_switcher.image_decoding'), 
        'grid_6_image_cache_disable' => $config->get('fs_switcher.cache_disable'), 
        'grid_6_image_border' => $config->get('fs_switcher.image_border'), 
        'grid_6_icon_color' => $config->get('fs_switcher.icon_color'), 
        'grid_6_image_align' => $config->get('fs_switcher.image_align'), 
        'grid_6_image_grid_width' => $config->get('fs_switcher.grid_width'), 
        'grid_6_image_grid_column_gap' => $config->get('fs_switcher.grid_column_gap'), 
        'grid_6_image_grid_row_gap' => $config->get('fs_switcher.grid_row_gap'), 
        'grid_6_image_grid_breakpoint' => $config->get('fs_switcher.grid_breakpoint'), 
        'grid_6_image_vertical_align' => $config->get('fs_switcher.image_vertical_align'), 
        'grid_6_image_svg_inline' => $config->get('fs_switcher.image_svg_inline'), 
        'grid_6_image_svg_animate' => $config->get('fs_switcher.image_svg_animate'), 
        'grid_6_image_svg_color' => $config->get('fs_switcher.image_svg_color'), 
        'grid_6_image_text_color' => $config->get('fs_switcher.text_color'), 
        'grid_6_image_margin_bottom' => $config->get('fs_switcher.image_margin_bottom'), 
        'grid_6_image_margin_top' => $config->get('fs_switcher.image_margin_top')
      ], 
      'fieldset' => [
        'default' => [
          'fields' => [[
              'label' => 'Grid', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['grid_6_position', 'grid_6_column_gap', 'grid_6_row_gap', 'grid_6_divider_top', 'grid_6_divider', 'grid_6_column_align', 'grid_6_row_align', 'grid_6_text_align', 'grid_6_text_align_breakpoint', 'grid_6_text_align_fallback', 'grid_6_slider', '_grid_6_slider_panel', 'grid_6_margin', 'grid_6_visibility']
            ], [
              'label' => 'Columns', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['grid_6_default', 'grid_6_small', 'grid_6_medium', 'grid_6_large', 'grid_6_xlarge']
            ], [
              'label' => 'Panel', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['grid_6_panel_style', 'grid_6_panel_card_offset', 'grid_6_panel_padding']
            ], [
              'label' => 'Image', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => [[
                  'label' => 'Width/Height', 
                  'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.', 
                  'type' => 'grid', 
                  'width' => '1-3', 
                  'fields' => ['grid_6_image_width', 'grid_6_image_height', 'grid_6_icon_width']
                ], 'grid_6_image_loading', 'grid_6_image_fetchpriority', 'grid_6_image_decoding', 'grid_6_image_cache_disable', 'grid_6_image_border', 'grid_6_icon_color', 'grid_6_image_align', 'grid_6_image_grid_width', 'grid_6_image_grid_column_gap', 'grid_6_image_grid_row_gap', 'grid_6_image_grid_breakpoint', 'grid_6_image_vertical_align', 'grid_6_image_svg_inline', 'grid_6_image_svg_animate', 'grid_6_image_svg_color', 'grid_6_image_text_color', 'grid_6_image_margin_top', 'grid_6_image_margin_bottom']
            ]]
        ]
      ]
    ]
  ]
];
