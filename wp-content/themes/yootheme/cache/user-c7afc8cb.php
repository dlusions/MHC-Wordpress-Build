<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/core-condition-rules-wordpress/User/config/user.json

return [
  'name' => 'yooessentials_access_user', 
  'title' => 'User', 
  'group' => 'site', 
  'collection' => 'User', 
  'collectionDescription' => 'Validates against the current user.', 
  'description' => 'Validates against the current user.', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/core-condition-rules-wordpress/User/assets/user.svg', $file), 
  'fields' => [
    'users' => [
      'label' => 'Selection', 
      'source' => true, 
      'type' => 'textarea', 
      'attrs' => [
        'rows' => 4, 
        'placeholder' => '346
username'
      ], 
      'description' => 'The list of User id or usernames that the current user must match. Separate the entries with a comma and/or new line.'
    ]
  ]
];
