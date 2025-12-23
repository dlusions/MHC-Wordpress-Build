<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/core-condition-rules-wordpress/UserGroup/config.json

return [
  'name' => 'yooessentials_access_itthinx_usergroup', 
  'title' => 'User Group', 
  'group' => 'site', 
  'collection' => 'User', 
  'description' => 'Validates against the Current User Group (3rd Party).', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/core-condition-rules-wordpress/UserGroup/icon.svg', $file)
];
