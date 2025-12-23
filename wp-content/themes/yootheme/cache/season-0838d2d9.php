<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/core-condition-rules/Calendar/config/season.json

return [
  'name' => 'yooessentials_access_season', 
  'title' => 'Season', 
  'group' => 'datetime', 
  'collection' => 'Calendar', 
  'description' => 'Validates against the current meteorological season.', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/core-condition-rules/Calendar/assets/season.svg', $file), 
  'fields' => [
    'seasons' => [
      'label' => 'Selection', 
      'type' => 'select', 
      'source' => true, 
      'description' => 'The seasons that the current date must match. Timezone from Site configuration is automatically applied. Use the shift or ctrl/cmd key to select multiple entries.', 
      'attrs' => [
        'multiple' => true
      ], 
      'options' => [
        'Winter' => 'winter', 
        'Spring' => 'spring', 
        'Summer' => 'summer', 
        'Autumn' => 'fall'
      ]
    ], 
    'hemisphere' => [
      'label' => 'Hemisphere', 
      'type' => 'select', 
      'source' => true, 
      'options' => [
        'Northern' => '', 
        'Southern' => 'southern', 
        'Australia' => 'australia'
      ], 
      'description' => 'The Hemisphere from which to calculate the current season.'
    ]
  ]
];
