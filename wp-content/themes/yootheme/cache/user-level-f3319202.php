<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/core-condition-rules-wordpress/User/config/user-level.json

return [
  'name' => 'yooessentials_access_useraccess', 
  'title' => 'User Level', 
  'group' => 'site', 
  'collection' => 'User', 
  'description' => 'Validates against the current User Role Level.', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/core-condition-rules-wordpress/User/assets/user-level.svg', $file)
];
