<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/form-elements/form_radio/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'yooessentials_form_radio', 
  'title' => 'Radio', 
  'group' => 'Form Essentials', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form_radio/icon.svg', $file), 
  'iconSmall' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form_radio/icon.svg', $file), 
  'element' => true, 
  'submittable' => true, 
  'width' => 500, 
  'defaults' => [
    'margin' => 'default', 
    'control_layout' => 'vertical', 
    'control_id_inherit' => true
  ], 
  'placeholder' => [
    'children' => [[
        'type' => 'yooessentials_form_option', 
        'props' => [
          'text' => 'Option 1', 
          'value' => 'option-1'
        ]
      ], [
        'type' => 'yooessentials_form_option', 
        'props' => [
          'text' => 'Option 2', 
          'value' => 'option-2'
        ]
      ], [
        'type' => 'yooessentials_form_option', 
        'props' => [
          'text' => 'Option 3', 
          'value' => 'option-3'
        ]
      ]]
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file)
  ], 
  'fields' => [
    'control_layout' => [
      'label' => 'Layout', 
      'description' => 'The output layout.', 
      'type' => 'select', 
      'options' => [
        'Vertical' => 'vertical', 
        'Horizontal' => 'horizontal'
      ]
    ], 
    'control_label' => $config->get('yooessentials.form.fields.control_label'), 
    'control_name' => $config->get('yooessentials.form.fields.control_name'), 
    'control_id' => $config->get('yooessentials.form.fields.control_id'), 
    'control_id_inherit' => $config->get('yooessentials.form.fields.control_id_inherit'), 
    'control_value' => $config->get('yooessentials.form.fields.control_value'), 
    'control_options' => $config->get('yooessentials.form.fields.control_options'), 
    'control_required' => $config->get('yooessentials.form.fields.control_required'), 
    'control_error_message' => $config->get('yooessentials.form.fields.control_error_message'), 
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
          'fields' => ['control_name', 'control_value', 'control_required', 'control_error_message', 'control_options']
        ], [
          'title' => 'Field', 
          'fields' => ['control_label', 'control_layout', 'control_id', 'control_id_inherit']
        ], [
          'title' => 'Settings', 
          'fields' => [[
              'label' => 'General', 
              'type' => 'group', 
              'fields' => ['position', 'position_left', 'position_right', 'position_top', 'position_bottom', 'position_z_index', 'margin', 'margin_remove_top', 'margin_remove_bottom', 'maxwidth', 'maxwidth_breakpoint', 'block_align', 'block_align_breakpoint', 'block_align_fallback', 'text_align', 'text_align_breakpoint', 'text_align_fallback', 'animation', '_parallax_button', 'visibility']
            ]]
        ], $config->get('builder.advanced')]
    ]
  ]
];
