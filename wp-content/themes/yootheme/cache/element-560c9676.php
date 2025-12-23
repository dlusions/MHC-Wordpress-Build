<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/wp-djpopup-1.2.6/modules/builder/triggers/on_hover/element.json

return [
  'name' => 'on_hover', 
  'title' => 'DJ-Popup', 
  'container' => false, 
  'width' => 500, 
  'defaults' => [
    'link' => '', 
    'selector' => '', 
    'display_multiple' => 1
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
    ], 
    'display_multiple' => [
      'label' => 'Repeating', 
      'text' => 'Open a popup each time the trigger is called', 
      'type' => 'Checkbox'
    ]
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Settings', 
          'fields' => ['link', 'selector', 'display_multiple']
        ]]
    ]
  ]
];
