<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/core-condition-rules/Calendar/config/day.json

return [
  'name' => 'yooessentials_access_day', 
  'title' => 'Day', 
  'group' => 'datetime', 
  'collection' => 'Calendar', 
  'collectionDescription' => 'Validates against dates or seasons.', 
  'description' => 'Validates against the current day.', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/core-condition-rules/Calendar/assets/day.svg', $file), 
  'fields' => [
    'days' => [
      'label' => 'Selection', 
      'type' => 'select', 
      'source' => true, 
      'description' => 'The days that the current date must match. Timezone from Site configuration is automatically applied. Use the shift or ctrl/cmd key to select multiple entries.', 
      'attrs' => [
        'multiple' => true, 
        'class' => 'uk-height-small'
      ], 
      'options' => [
        'Monday' => '1', 
        'Tuesday' => '2', 
        'Wednesday' => '3', 
        'Thursday' => '4', 
        'Friday' => '5', 
        'Saturday' => '6', 
        'Sunday' => '7'
      ]
    ]
  ]
];
