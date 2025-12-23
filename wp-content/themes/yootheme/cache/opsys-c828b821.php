<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/core-condition-rules/Device/config/opsys.json

return [
  'name' => 'yooessentials_access_os', 
  'title' => 'Operating System', 
  'group' => 'device', 
  'description' => 'Validates against the Operating System.', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/core-condition-rules/Device/assets/opsys.svg', $file)
];
