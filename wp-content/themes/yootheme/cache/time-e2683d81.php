<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/core-condition-rules/Datetime/config/time.json

return [
  'name' => 'yooessentials_access_time', 
  'title' => 'Time', 
  'group' => 'datetime', 
  'collection' => 'Date & Time', 
  'description' => 'Validates against the current time.', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/core-condition-rules/Datetime/assets/time.svg', $file), 
  'fields' => [
    'publish_up' => [
      'label' => 'From', 
      'type' => 'yooessentials-time', 
      'description' => 'The start time in <code>H:i</code> format.', 
      'source' => true
    ], 
    'publish_down' => [
      'label' => 'Until', 
      'type' => 'yooessentials-time', 
      'description' => 'The end time in <code>H:i</code> format.', 
      'source' => true
    ]
  ]
];
