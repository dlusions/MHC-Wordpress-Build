<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/form-elements/form_button_submit/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'yooessentials_form_button_item', 
  'title' => 'Submit', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form_button/icon.svg', $file), 
  'iconSmall' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form_button/icon.svg', $file), 
  'width' => 500, 
  'defaults' => [
    'button_style' => 'default', 
    'icon_align' => 'left'
  ], 
  'placeholder' => [
    'props' => [
      'content' => 'Submit', 
      'icon' => ''
    ]
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file)
  ], 
  'fields' => [
    'content' => [
      'label' => 'Content', 
      'source' => true
    ], 
    'icon' => [
      'label' => 'Icon', 
      'description' => 'Pick an optional icon.', 
      'type' => 'icon', 
      'source' => true
    ], 
    'icon_align' => [
      'label' => 'Icon Alignment', 
      'description' => 'Choose the icon position.', 
      'type' => 'select', 
      'options' => [
        'Left' => 'left', 
        'Right' => 'right'
      ], 
      'enable' => 'icon'
    ], 
    'button_style' => [
      'label' => 'Style', 
      'description' => 'Set the button style.', 
      'type' => 'select', 
      'options' => [
        'Default' => 'default', 
        'Primary' => 'primary', 
        'Secondary' => 'secondary', 
        'Danger' => 'danger', 
        'Text' => 'text', 
        'Link' => '', 
        'Link Muted' => 'link-muted', 
        'Link Text' => 'link-text'
      ]
    ], 
    'status' => $config->get('builder.statusItem'), 
    'source' => $config->get('builder.source')
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Content', 
          'fields' => ['content', 'button_style', 'icon', 'icon_align']
        ], $config->get('builder.advancedItem')]
    ]
  ]
];
