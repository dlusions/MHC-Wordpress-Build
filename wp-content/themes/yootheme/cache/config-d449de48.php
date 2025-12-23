<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/core-condition-rules-wordpress/Language/config.json

return [
  'name' => 'yooessentials_access_language', 
  'title' => 'Language', 
  'group' => 'site', 
  'description' => 'Validates against the site language.', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/core-condition-rules-wordpress/Language/icon.svg', $file)
];
