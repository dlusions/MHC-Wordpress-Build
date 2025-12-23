<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/core-condition-rules/Datetime/config/date.json

return [
  'name' => 'yooessentials_access_date', 
  'title' => 'Date', 
  'group' => 'datetime', 
  'collection' => 'Date & Time', 
  'collectionDescription' => 'Validates against date and time.', 
  'description' => 'Validates against the current date.', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/core-condition-rules/Datetime/assets/date.svg', $file), 
  'fields' => [
    'publish_up' => [
      'label' => 'From', 
      'type' => 'yooessentials-date', 
      'description' => 'The start date in <code>Y-m-d</code> format.', 
      'source' => true
    ], 
    'publish_down' => [
      'label' => 'Until', 
      'type' => 'yooessentials-date', 
      'description' => 'The end date in <code>Y-m-d</code> format.', 
      'source' => true
    ]
  ]
];
