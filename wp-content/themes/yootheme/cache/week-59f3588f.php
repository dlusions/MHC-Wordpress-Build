<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/core-condition-rules/Calendar/config/week.json

return [
  'name' => 'yooessentials_access_week', 
  'title' => 'Week', 
  'group' => 'datetime', 
  'collection' => 'Calendar', 
  'description' => 'Validates against the current yearly week.', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/core-condition-rules/Calendar/assets/week.svg', $file), 
  'fields' => [
    'weeks' => [
      'label' => 'List', 
      'type' => 'textarea', 
      'description' => 'A list or range of weeks in a year that the current date must match, considering that in average a year has 52 weeks and the week starts in Monday. Separate the entries with a comma and/or new line.', 
      'source' => true, 
      'attrs' => [
        'rows' => 4, 
        'placeholder' => '2
4-8
52'
      ]
    ]
  ]
];
