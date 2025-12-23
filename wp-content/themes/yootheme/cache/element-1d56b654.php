<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/form-elements/form_range/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'yooessentials_form_range', 
  'title' => 'Range', 
  'group' => 'Form Essentials', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form_range/icon.svg', $file), 
  'iconSmall' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form_range/icon.svg', $file), 
  'width' => 500, 
  'element' => true, 
  'submittable' => true, 
  'defaults' => [
    'margin' => 'default', 
    'control_min' => 0, 
    'control_max' => 100, 
    'control_step' => 1, 
    'control_id_inherit' => true
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file)
  ], 
  'fields' => [
    'control_attrs' => $config->get('yooessentials.form.fields.control_attrs'), 
    'control_name' => $config->get('yooessentials.form.fields.control_name'), 
    'control_label' => $config->get('yooessentials.form.fields.control_label'), 
    'control_id' => $config->get('yooessentials.form.fields.control_id'), 
    'control_id_inherit' => $config->get('yooessentials.form.fields.control_id_inherit'), 
    'control_size' => $config->get('yooessentials.form.fields.control_size'), 
    'control_width' => $config->get('yooessentials.form.fields.control_width'), 
    'control_value' => $config->get('yooessentials.form.fields.control_value'), 
    'control_autofocus' => $config->get('yooessentials.form.fields.control_autofocus'), 
    'control_min' => $config->get('yooessentials.form.fields.control_min'), 
    'control_max' => $config->get('yooessentials.form.fields.control_max'), 
    'control_step' => $config->get('yooessentials.form.fields.control_step'), 
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
          'fields' => ['control_name', 'control_value', 'control_min', 'control_max', 'control_step', 'control_error_message']
        ], [
          'title' => 'Field', 
          'fields' => ['control_label', 'control_id', 'control_id_inherit', 'control_attrs']
        ], [
          'title' => 'Settings', 
          'fields' => [[
              'label' => 'Field', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['control_autofocus', 'control_size', 'control_width', 'control_rows']
            ], [
              'label' => 'General', 
              'type' => 'group', 
              'fields' => ['position', 'position_left', 'position_right', 'position_top', 'position_bottom', 'position_z_index', 'margin', 'margin_remove_top', 'margin_remove_bottom', 'maxwidth', 'maxwidth_breakpoint', 'block_align', 'block_align_breakpoint', 'block_align_fallback', 'text_align', 'text_align_breakpoint', 'text_align_fallback', 'animation', '_parallax_button', 'visibility']
            ]]
        ], $config->get('builder.advanced')]
    ]
  ]
];
