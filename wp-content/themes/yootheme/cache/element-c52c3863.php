<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/wp-djpopup-1.2.6/modules/builder/triggers/on_page_load/element.json

return [
  'name' => 'on_page_load', 
  'title' => 'DJ-Popup', 
  'container' => false, 
  'width' => 500, 
  'defaults' => [
    'delay' => 0
  ], 
  'fields' => [
    'delay' => [
      'label' => 'Delay', 
      'text' => 'Specify the time after which the action should be executed', 
      'type' => 'number', 
      'default' => 0
    ]
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Settings', 
          'fields' => ['delay']
        ]]
    ]
  ]
];
