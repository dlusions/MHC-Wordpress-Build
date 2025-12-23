<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/core-condition-rules/Customizer/config.json

return [
  'name' => 'yooessentials_access_customizer', 
  'title' => 'Customizer', 
  'group' => 'site', 
  'description' => 'Validates if the YOOtheme Pro Customizer session is active.', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/core-condition-rules/Customizer/icon.svg', $file)
];
