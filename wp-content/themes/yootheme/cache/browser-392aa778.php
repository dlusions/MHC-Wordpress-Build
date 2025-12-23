<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/core-condition-rules/Device/config/browser.json

return [
  'name' => 'yooessentials_access_browser', 
  'title' => 'Browser', 
  'group' => 'device', 
  'description' => 'Validates against the browser.', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/core-condition-rules/Device/assets/browser.svg', $file)
];
