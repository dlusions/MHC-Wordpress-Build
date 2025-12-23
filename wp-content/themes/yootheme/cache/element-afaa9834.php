<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/element/elements/social_sharing/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'yooessentials_social_sharing', 
  'title' => 'Social Sharing', 
  'group' => 'Essentials', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/element/elements/social_sharing/assets/icon.svg', $file), 
  'iconSmall' => $filter->apply('url', '~yooessentials_url/modules/element/elements/social_sharing/assets/icon.svg', $file), 
  'element' => true, 
  'container' => true, 
  'width' => 500, 
  'defaults' => [
    'link_style' => 'button', 
    'grid' => 'horizontal', 
    'grid_column_gap' => 'small', 
    'grid_row_gap' => 'small', 
    'image_svg_inline' => true, 
    'margin' => 'default'
  ], 
  'placeholder' => [
    'children' => [[
        'type' => 'yooessentials_social_sharing_item', 
        'props' => [
          'network' => 'x'
        ]
      ], [
        'type' => 'yooessentials_social_sharing_item', 
        'props' => [
          'network' => 'facebook'
        ]
      ], [
        'type' => 'yooessentials_social_sharing_item', 
        'props' => [
          'network' => 'whatsapp'
        ]
      ], [
        'type' => 'yooessentials_social_sharing_item', 
        'props' => [
          'network' => 'telegram'
        ]
      ], [
        'type' => 'yooessentials_social_sharing_item', 
        'props' => [
          'network' => 'linkedin'
        ]
      ], [
        'type' => 'yooessentials_social_sharing_item', 
        'props' => [
          'network' => 'pinterest'
        ]
      ], [
        'type' => 'yooessentials_social_sharing_item', 
        'props' => [
          'network' => 'xing'
        ]
      ], [
        'type' => 'yooessentials_social_sharing_viber', 
        'props' => [
          'text' => 'Check this out!'
        ]
      ], [
        'type' => 'yooessentials_social_sharing_mailto', 
        'props' => [
          'email_subject' => 'Check this out!'
        ]
      ]]
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file), 
    'content' => $filter->apply('path', './templates/content.php', $file)
  ], 
  'fields' => [
    'content' => [
      'label' => 'Items', 
      'type' => 'yooessentials-content-items', 
      'title' => 'network', 
      'button' => 'Add Item', 
      'filter' => [
        'name' => 'yooessentials_social_sharing_(.*)'
      ]
    ], 
    'link_style' => [
      'label' => 'Style', 
      'type' => 'select', 
      'options' => [
        'Icon Link' => '', 
        'Icon Button' => 'button', 
        'Link' => 'link', 
        'Link Muted' => 'muted', 
        'Link Text' => 'text', 
        'Link Reset' => 'reset', 
        'Iconnav' => 'iconnav', 
        'Thumbnav' => 'thumbnav'
      ]
    ], 
    'grid' => [
      'label' => 'Grid', 
      'type' => 'select', 
      'options' => [
        'Horizontal' => 'horizontal', 
        'Vertical' => 'vertical'
      ]
    ], 
    'grid_vertical_breakpoint' => [
      'label' => 'Grid Breakpoint', 
      'description' => 'Set the breakpoint from which grid items will align side by side.', 
      'type' => 'select', 
      'options' => [
        'Always' => '', 
        'Small (Phone Landscape)' => 's', 
        'Medium (Tablet Landscape)' => 'm', 
        'Large (Desktop)' => 'l', 
        'X-Large (Large Screens)' => 'xl'
      ], 
      'enable' => 'grid == \'vertical\' && !$match(link_style, \'iconnav|thumbnav\')'
    ], 
    'grid_column_gap' => [
      'label' => 'Column Gap', 
      'description' => 'Set the size of the gap between the grid columns.', 
      'type' => 'select', 
      'options' => [
        'Small' => 'small', 
        'Medium' => 'medium', 
        'Default' => '', 
        'Large' => 'large', 
        'None' => 'collapse'
      ], 
      'enable' => '!$match(link_style, \'iconnav|thumbnav\') || ($match(link_style, \'iconnav|thumbnav\') && grid == \'horizontal\')'
    ], 
    'grid_row_gap' => [
      'label' => 'Row Gap', 
      'description' => 'Set the size of the gap between the grid rows.', 
      'type' => 'select', 
      'options' => [
        'Small' => 'small', 
        'Medium' => 'medium', 
        'Default' => '', 
        'Large' => 'large', 
        'None' => 'collapse'
      ], 
      'enable' => '!$match(link_style, \'iconnav|thumbnav\') || ($match(link_style, \'iconnav|thumbnav\') && grid == \'horizontal\')'
    ], 
    'icon_width' => [
      'label' => 'Icon Width', 
      'description' => 'Set the icon width.'
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
    'image_loading' => [
      'label' => 'Loading', 
      'description' => 'By default, images are loaded lazy. Enable eager loading for images in the initial viewport.', 
      'type' => 'checkbox', 
      'text' => 'Load image eagerly'
    ], 
    'link_target' => [
      'label' => 'Link Target', 
      'type' => 'checkbox', 
      'text' => 'Open in a new window'
    ], 
    'link_aria_label' => [
      'label' => 'ARIA Label', 
      'description' => 'Enter a descriptive text label to make it accessible if the link has no visible text.'
    ], 
    'image_svg_inline' => [
      'label' => 'Inline SVG', 
      'description' => 'Inject SVG images into the page markup so that they can easily be styled with CSS.', 
      'type' => 'checkbox', 
      'text' => 'Make SVG stylable with CSS'
    ], 
    'position' => $config->get('builder.position'), 
    'position_left' => $config->get('builder.position_left'), 
    'position_right' => $config->get('builder.position_right'), 
    'position_top' => $config->get('builder.position_top'), 
    'position_bottom' => $config->get('builder.position_bottom'), 
    'position_z_index' => $config->get('builder.position_z_index'), 
    'margin' => $config->get('builder.margin'), 
    'margin_remove_top' => $config->get('builder.margin_remove_top'), 
    'margin_remove_bottom' => $config->get('builder.margin_remove_bottom'), 
    'maxwidth' => $config->get('builder.maxwidth'), 
    'maxwidth_breakpoint' => $config->get('builder.maxwidth_breakpoint'), 
    'block_align' => $config->get('builder.block_align'), 
    'block_align_breakpoint' => $config->get('builder.block_align_breakpoint'), 
    'block_align_fallback' => $config->get('builder.block_align_fallback'), 
    'text_align' => $config->get('builder.text_align'), 
    'text_align_breakpoint' => $config->get('builder.text_align_breakpoint'), 
    'text_align_fallback' => $config->get('builder.text_align_fallback'), 
    'animation' => $config->get('builder.animation'), 
    '_parallax_button' => $config->get('builder._parallax_button'), 
    'visibility' => $config->get('builder.visibility'), 
    'name' => $config->get('builder.name'), 
    'status' => $config->get('builder.status'), 
    'id' => $config->get('builder.id'), 
    'class' => $config->get('builder.cls'), 
    'attributes' => $config->get('builder.attrs'), 
    'source' => $config->get('builder.source'), 
    'transform' => $config->get('builder.transform'), 
    'css' => [
      'label' => 'CSS', 
      'description' => 'Enter your own custom CSS. The following selectors will be prefixed automatically for this element: <code>.el-element</code>, <code>.el-link</code>', 
      'type' => 'editor', 
      'editor' => 'code', 
      'mode' => 'css', 
      'attrs' => [
        'debounce' => 500
      ]
    ]
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
              'label' => 'Social Icons', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['link_style', 'grid', 'grid_vertical_breakpoint', 'grid_column_gap', 'grid_row_gap']
            ], [
              'label' => 'Image', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['icon_width', [
                  'label' => 'Width/Height', 
                  'description' => 'Setting just one value preserves the original proportions. The image will be resized and cropped automatically, and where possible, high resolution images will be auto-generated.', 
                  'type' => 'grid', 
                  'width' => '1-2', 
                  'fields' => ['image_width', 'image_height']
                ], 'image_loading', 'image_svg_inline']
            ], [
              'label' => 'Link', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['link_target', 'link_aria_label']
            ], [
              'label' => 'General', 
              'type' => 'group', 
              'fields' => ['position', 'position_left', 'position_right', 'position_top', 'position_bottom', 'position_z_index', 'margin', 'margin_remove_top', 'margin_remove_bottom', 'maxwidth', 'maxwidth_breakpoint', 'block_align', 'block_align_breakpoint', 'block_align_fallback', 'text_align', 'text_align_breakpoint', 'text_align_fallback', 'animation', '_parallax_button', 'visibility']
            ]]
        ], $config->get('builder.advanced')]
    ]
  ]
];
