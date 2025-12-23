<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/form-elements/form_option/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'yooessentials_form_option', 
  'title' => 'Option', 
  'width' => 500, 
  'defaults' => [
    'disabled' => false
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file), 
    'content' => $filter->apply('path', './templates/content.php', $file)
  ], 
  'fields' => [
    'value' => [
      'label' => 'Value', 
      'description' => 'The option value. It must be set and unique among its siblings.', 
      'source' => true
    ], 
    'text' => [
      'label' => 'Text', 
      'description' => 'The option text. Defaults to the option value if omitted.', 
      'type' => 'editor', 
      'source' => true
    ], 
    'id' => [
      'label' => 'ID', 
      'description' => 'The option ID attribute, only appliable for Checkbox and Radio. Overrides any ID set in the parent element.', 
      'source' => true
    ], 
    'disabled' => [
      'label' => 'State', 
      'description' => 'The option state. A disabled option is unusable and un-clickable.', 
      'type' => 'select', 
      'options' => [
        'Enabled' => false, 
        'Disabled' => true
      ], 
      'source' => true
    ], 
    'attributes' => $config->get('yooessentials.form.fields.control_attrs'), 
    'status' => $config->get('builder.statusItem'), 
    'source' => $config->get('builder.source')
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Content', 
          'fields' => ['value', 'text', 'disabled', 'id', 'attributes']
        ], $config->get('builder.advancedItem')]
    ]
  ]
];
