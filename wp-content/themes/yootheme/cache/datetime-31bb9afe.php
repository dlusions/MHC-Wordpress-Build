<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/core-condition-rules/Datetime/config/datetime.json

return [
  'name' => 'yooessentials_access_datetime', 
  'title' => 'Datetime', 
  'group' => 'datetime', 
  'collection' => 'Date & Time', 
  'description' => 'Validates against the current date & time.', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/core-condition-rules/Datetime/assets/datetime.svg', $file), 
  'fields' => [
    'publish_up' => [
      'label' => 'From', 
      'type' => 'yooessentials-datetime', 
      'description' => 'The start datetime in <code>Y-m-d H:i</code> format.', 
      'source' => true, 
      'valueFormat' => 'yyyy-MM-dd HH:mm', 
      'displayFormat' => 'yyyy-MM-dd HH:mm'
    ], 
    'publish_down' => [
      'label' => 'Until', 
      'type' => 'yooessentials-datetime', 
      'description' => 'The end datetime in <code>Y-m-d H:i</code> format.', 
      'source' => true, 
      'valueFormat' => 'yyyy-MM-dd HH:mm', 
      'displayFormat' => 'yyyy-MM-dd HH:mm'
    ]
  ]
];
