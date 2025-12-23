<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/wp-djpopup-1.2.6/modules/builder/triggers/on_scroll/element.json

return [
  'name' => 'on_scroll', 
  'title' => 'DJ-Popup', 
  'container' => false, 
  'width' => 500, 
  'defaults' => [
    'distance' => 50
  ], 
  'fields' => [
    'distance' => [
      'label' => 'Distance', 
      'text' => 'Specify the % distance the user must scroll to see the popup', 
      'type' => 'range', 
      'required' => true, 
      'default' => 50
    ]
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Settings', 
          'fields' => ['distance']
        ]]
    ]
  ]
];
