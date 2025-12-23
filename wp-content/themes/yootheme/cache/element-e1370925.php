<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/form-elements/form_fieldset/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'yooessentials_form_fieldset', 
  'title' => 'Fieldset', 
  'group' => 'Form Essentials', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form_fieldset/icon.svg', $file), 
  'iconSmall' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form_fieldset/icon.svg', $file), 
  'element' => true, 
  'container' => true, 
  'fragment' => true, 
  'width' => 500, 
  'defaults' => [
    'margin' => 'default', 
    'layout' => 'horizontal', 
    'fields_show_label' => true
  ], 
  'placeholder' => [
    'children' => [[
        'type' => 'yooessentials_form_input'
      ], [
        'type' => 'yooessentials_form_textarea'
      ]]
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file)
  ], 
  'fields' => [
    'content' => [
      'type' => 'builder-fragment'
    ], 
    'caption' => [
      'label' => 'Caption', 
      'description' => 'The Fieldset Caption'
    ], 
    'layout' => [
      'label' => 'Layout', 
      'description' => 'Fields layout modifier', 
      'type' => 'select', 
      'options' => [
        'Horizontal' => 'horizontal', 
        'Stacked' => 'stacked'
      ]
    ], 
    'fields_show_label' => [
      'label' => 'Display', 
      'type' => 'checkbox', 
      'text' => 'Show fields label'
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
          'fields' => ['content', 'fields_show_label']
        ], [
          'title' => 'Settings', 
          'fields' => [[
              'label' => 'Fieldset', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['caption', 'layout']
            ], [
              'label' => 'General', 
              'type' => 'group', 
              'fields' => ['position', 'position_left', 'position_right', 'position_top', 'position_bottom', 'position_z_index', 'margin', 'margin_remove_top', 'margin_remove_bottom', 'maxwidth', 'maxwidth_breakpoint', 'block_align', 'block_align_breakpoint', 'block_align_fallback', 'text_align', 'text_align_breakpoint', 'text_align_fallback', 'animation', '_parallax_button', 'visibility']
            ]]
        ], $config->get('builder.advanced')]
    ]
  ]
];
