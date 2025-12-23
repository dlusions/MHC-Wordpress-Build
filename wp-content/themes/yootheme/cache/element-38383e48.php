<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/element/elements/markdown/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'yooessentials_markdown', 
  'title' => 'Markdown', 
  'group' => 'Essentials', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/element/elements/markdown/assets/icon.svg', $file), 
  'iconSmall' => $filter->apply('url', '~yooessentials_url/modules/element/elements/markdown/assets/icon.svg', $file), 
  'element' => true, 
  'width' => 500, 
  'defaults' => [
    'margin' => 'default', 
    'column_breakpoint' => 'm'
  ], 
  'placeholder' => [
    'props' => [
      'content' => '# Headline
Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'
    ]
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file), 
    'content' => $filter->apply('path', './templates/content.php', $file)
  ], 
  'fields' => [
    'content' => [
      'type' => 'editor', 
      'editor' => 'code', 
      'source' => true
    ], 
    'heading_remove' => [
      'label' => 'Remove H1', 
      'text' => 'Remove H1 tag', 
      'type' => 'checkbox'
    ], 
    'heading_starting_level' => [
      'label' => 'Heading Starting Level', 
      'description' => 'Select the level at which the Headings will start at. Subsequent levels will keep their level difference.', 
      'type' => 'select', 
      'options' => [
        'Current' => '', 
        'H1' => 1, 
        'H2' => 2, 
        'H3' => 3, 
        'H4' => 4, 
        'H5' => 5, 
        'H6' => 6
      ]
    ], 
    'dropcap' => [
      'label' => 'Drop Cap', 
      'description' => 'Display the first letter of the paragraph as a large initial.', 
      'type' => 'checkbox', 
      'text' => 'Enable drop cap'
    ], 
    'text_style' => [
      'label' => 'Text Style', 
      'description' => 'Select a predefined text style, including color, size and font-family.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Lead' => 'lead', 
        'Meta' => 'meta'
      ]
    ], 
    'text_size' => [
      'label' => 'Text Size', 
      'description' => 'Select the text size.', 
      'type' => 'select', 
      'options' => [
        'None' => '', 
        'Small' => 'small', 
        'Large' => 'large'
      ], 
      'show' => '!text_style'
    ], 
    'text_color' => [
      'label' => 'Text Color', 
      'description' => 'Select the text color. If the Background option is selected, styles that don\'t apply a background image use the primary color instead.', 
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
    'column' => [
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
      ]
    ], 
    'column_divider' => [
      'description' => 'Show a divider between text columns.', 
      'type' => 'checkbox', 
      'text' => 'Show dividers', 
      'enable' => 'column'
    ], 
    'column_breakpoint' => [
      'label' => 'Columns Breakpoint', 
      'description' => 'Set the device width from which the text columns should apply.', 
      'type' => 'select', 
      'options' => [
        'Always' => '', 
        'Small (Phone Landscape)' => 's', 
        'Medium (Tablet Landscape)' => 'm', 
        'Large (Desktop)' => 'l', 
        'X-Large (Large Screens)' => 'xl'
      ], 
      'enable' => 'column'
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
    'text_align' => $config->get('builder.text_align_justify'), 
    'text_align_breakpoint' => $config->get('builder.text_align_breakpoint'), 
    'text_align_fallback' => $config->get('builder.text_align_justify_fallback'), 
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
      'description' => 'Enter your own custom CSS. The following selectors will be prefixed automatically for this element: <code>.el-element</code>', 
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
          'fields' => ['content', 'remove_heading']
        ], [
          'title' => 'Settings', 
          'fields' => [[
              'label' => 'Text', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['dropcap', 'heading_remove', 'heading_starting_level', 'text_style', 'text_size', 'text_color', 'column', 'column_divider', 'column_breakpoint']
            ], [
              'label' => 'General', 
              'type' => 'group', 
              'fields' => ['position', 'position_left', 'position_right', 'position_top', 'position_bottom', 'position_z_index', 'margin', 'margin_remove_top', 'margin_remove_bottom', 'maxwidth', 'maxwidth_breakpoint', 'block_align', 'block_align_breakpoint', 'block_align_fallback', 'text_align', 'text_align_breakpoint', 'text_align_fallback', 'animation', '_parallax_button', 'visibility']
            ]]
        ], $config->get('builder.advanced')]
    ]
  ]
];
