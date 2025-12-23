<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/wp-djpopup-1.2.6/modules/builder/triggers/scroll_to/element.json

return [
  'name' => 'scroll_to', 
  'title' => 'DJ-Popup', 
  'container' => false, 
  'width' => 500, 
  'defaults' => [
    'link' => '', 
    'selector' => '', 
    'display_multiple' => 0
  ], 
  'fields' => [
    'link' => [
      'label' => 'Link', 
      'description' => 'You can specify the link to which the trigger will be assigned', 
      'type' => 'text', 
      'enable' => '!selector'
    ], 
    'selector' => [
      'label' => 'Selector', 
      'description' => 'Specify the selector of the element to which the action is to be attached', 
      'type' => 'text', 
      'enable' => '!link'
    ]
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Settings', 
          'fields' => ['link', 'selector']
        ]]
    ]
  ]
];
