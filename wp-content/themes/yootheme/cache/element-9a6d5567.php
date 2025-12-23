<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/form-elements/form_input_url/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'yooessentials_form_input_url', 
  'title' => 'Url', 
  'group' => 'Form Essentials', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form_input/icon.svg', $file), 
  'iconSmall' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form_input/icon.svg', $file), 
  'width' => 500, 
  'submittable' => true, 
  'defaults' => [
    'control_id_inherit' => true
  ], 
  'fields' => [
    'control_value' => [
      'label' => $config->get('yooessentials.form.fields.control_value.label'), 
      'description' => $config->get('yooessentials.form.fields.control_value.description'), 
      'source' => $config->get('yooessentials.form.fields.control_value.source'), 
      'attrs' => [
        'type' => 'url'
      ]
    ], 
    'control_attrs' => $config->get('yooessentials.form.fields.control_attrs'), 
    'control_name' => $config->get('yooessentials.form.fields.control_name'), 
    'control_label' => $config->get('yooessentials.form.fields.control_label'), 
    'control_id' => $config->get('yooessentials.form.fields.control_id'), 
    'control_id_inherit' => $config->get('yooessentials.form.fields.control_id_inherit'), 
    'control_placeholder' => $config->get('yooessentials.form.fields.control_placeholder'), 
    'control_autofocus' => $config->get('yooessentials.form.fields.control_autofocus'), 
    'control_required' => $config->get('yooessentials.form.fields.control_required'), 
    'control_readonly' => $config->get('yooessentials.form.fields.control_readonly'), 
    'control_icon' => $config->get('yooessentials.form.fields.control_icon'), 
    'control_icon_align' => $config->get('yooessentials.form.fields.control_icon_align'), 
    'control_minlength' => $config->get('yooessentials.form.fields.control_minlength'), 
    'control_maxlength' => $config->get('yooessentials.form.fields.control_maxlength'), 
    'control_pattern' => $config->get('yooessentials.form.fields.control_pattern'), 
    'control_error_message' => $config->get('yooessentials.form.fields.control_error_message'), 
    'source' => $config->get('builder.source')
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Content', 
          'fields' => ['control_name', 'control_value', 'control_required', 'control_minlength', 'control_maxlength', 'control_pattern', 'control_error_message']
        ], [
          'title' => 'Field', 
          'fields' => ['control_label', 'control_placeholder', 'control_id', 'control_id_inherit', 'control_attrs']
        ], [
          'title' => 'Settings', 
          'fields' => [[
              'label' => 'Field', 
              'type' => 'group', 
              'fields' => ['control_readonly', 'control_autofocus', 'control_icon', 'control_icon_align']
            ]]
        ], $config->get('builder.advanced')]
    ]
  ]
];
