<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/form-elements/form_hidden/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'yooessentials_form_hidden', 
  'title' => 'Hidden', 
  'group' => 'Form Essentials', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form_hidden/icon.svg', $file), 
  'iconSmall' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form_hidden/icon.svg', $file), 
  'width' => 500, 
  'element' => true, 
  'submittable' => true, 
  'defaults' => [
    'control_id_inherit' => true, 
    'control_encrypt' => false
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file)
  ], 
  'fields' => [
    'control_value' => [
      'label' => 'Value', 
      'description' => 'The field value that will be hidden from the user interface although included in the form submission. For security reasons you might opt to encrypt the value while being exposed in the form.', 
      'source' => true
    ], 
    'control_encrypt' => [
      'text' => 'Encrypt while exposed', 
      'type' => 'checkbox'
    ], 
    'control_attrs' => $config->get('yooessentials.form.fields.control_attrs'), 
    'control_name' => $config->get('yooessentials.form.fields.control_name'), 
    'control_id' => $config->get('yooessentials.form.fields.control_id'), 
    'control_id_inherit' => $config->get('yooessentials.form.fields.control_id_inherit'), 
    'name' => $config->get('builder.name'), 
    'status' => $config->get('builder.status'), 
    'source' => $config->get('builder.source'), 
    'transform' => $config->get('builder.transform')
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Content', 
          'fields' => ['control_name', 'control_value', 'control_encrypt']
        ], [
          'title' => 'Field', 
          'fields' => ['control_id', 'control_id_inherit', 'control_attrs']
        ], $config->get('builder.advanced')]
    ]
  ]
];
