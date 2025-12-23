<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/form-elements/form_input/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'yooessentials_form_input', 
  'title' => 'Input', 
  'group' => 'Form Essentials', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form_input/icon.svg', $file), 
  'iconSmall' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form_input/icon.svg', $file), 
  'element' => true, 
  'container' => true, 
  'width' => 500, 
  'defaults' => [
    'show_label' => true, 
    'show_icon' => true, 
    'control_icon_align' => 'left', 
    'grid_column_gap' => 'small', 
    'grid_row_gap' => 'small', 
    'margin' => 'default', 
    'grid_columns_number' => 'auto'
  ], 
  'placeholder' => [
    'children' => [[
        'type' => 'yooessentials_form_input_text'
      ]]
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file)
  ], 
  'fields' => [
    'fields' => [
      'label' => 'Fields', 
      'type' => 'yooessentials-content-items', 
      'button' => 'Add Field', 
      'title' => 'control_name', 
      'txtEmpty' => 'No Fields Yet', 
      'icon' => true, 
      'modalTitle' => 'Fields', 
      'filter' => [
        'name' => 'yooessentials_form_input_(.*)'
      ]
    ], 
    'control_width' => [
      'label' => 'Width', 
      'description' => 'The field width size.', 
      'type' => 'select', 
      'options' => [
        'Default' => '', 
        'Large' => 'large', 
        'Medium' => 'medium', 
        'Small' => 'small', 
        'XSmall' => 'xsmall'
      ], 
      'enable' => '!fullwidth'
    ], 
    'fullwidth' => [
      'type' => 'checkbox', 
      'text' => 'Full width field'
    ], 
    'grid_columns_number' => [
      'label' => 'Number of Columns', 
      'description' => 'Set the number of grid columns for multiple inputs.', 
      'type' => 'select', 
      'options' => [
        'Auto' => 'auto', 
        1 => '1-1@m', 
        2 => '1-2@m', 
        3 => '1-3@m', 
        4 => '1-4@m', 
        5 => '1-5@m'
      ], 
      'enable' => '!fullwidth'
    ], 
    'grid_column_gap' => [
      'label' => 'Column Gap', 
      'description' => 'Set the size of the column gap between multiple inputs.', 
      'type' => 'select', 
      'options' => [
        'Small' => 'small', 
        'Medium' => 'medium', 
        'Default' => '', 
        'Large' => 'large'
      ]
    ], 
    'grid_row_gap' => [
      'label' => 'Row Gap', 
      'description' => 'Set the size of the row gap between multiple inputs.', 
      'type' => 'select', 
      'options' => [
        'Small' => 'small', 
        'Medium' => 'medium', 
        'Default' => '', 
        'Large' => 'large'
      ]
    ], 
    'show_label' => [
      'label' => 'Display', 
      'type' => 'checkbox', 
      'text' => 'Show the label'
    ], 
    'show_icon' => [
      'type' => 'checkbox', 
      'text' => 'Show the icon'
    ], 
    'control_size' => $config->get('yooessentials.form.fields.control_size'), 
    'control_icon' => $config->get('yooessentials.form.fields.control_icon'), 
    'control_icon_align' => $config->get('yooessentials.form.fields.control_icon_align'), 
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
          'fields' => ['fields', 'show_label', 'show_icon']
        ], [
          'title' => 'Settings', 
          'fields' => [[
              'label' => 'Field', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['control_size', 'control_width', 'fullwidth', 'control_icon', 'control_icon_align']
            ], [
              'label' => 'Columns', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['grid_columns_number', 'grid_column_gap', 'grid_row_gap']
            ], [
              'label' => 'General', 
              'type' => 'group', 
              'fields' => ['position', 'position_left', 'position_right', 'position_top', 'position_bottom', 'position_z_index', 'margin', 'margin_remove_top', 'margin_remove_bottom', 'maxwidth', 'maxwidth_breakpoint', 'block_align', 'block_align_breakpoint', 'block_align_fallback', 'text_align', 'text_align_breakpoint', 'text_align_fallback', 'animation', '_parallax_button', 'visibility']
            ]]
        ], $config->get('builder.advanced')]
    ]
  ]
];
