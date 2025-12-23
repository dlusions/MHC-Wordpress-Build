<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/core-condition-rules/IpAddress/config.json

return [
  'name' => 'yooessentials_access_ip', 
  'title' => 'IP Address', 
  'group' => 'device', 
  'description' => 'Validates against the IP Address.', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/core-condition-rules/IpAddress/icon.svg', $file), 
  'fields' => [
    'ips' => [
      'label' => 'Selection', 
      'type' => 'textarea', 
      'source' => true, 
      'attrs' => [
        'rows' => 4, 
        'placeholder' => '127.0.0.1
128.0-128.1
129'
      ], 
      'description' => 'The list of IP addresses and ranges that the device must match. Separate the entries with a comma and/or new line.'
    ]
  ]
];
