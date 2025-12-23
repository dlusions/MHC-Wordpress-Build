<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/core-condition-rules/Device/config/device.json

return [
  'name' => 'yooessentials_access_device', 
  'title' => 'Device', 
  'group' => 'device', 
  'description' => 'Validates against the device type.', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/core-condition-rules/Device/assets/device.svg', $file), 
  'fields' => [
    'devices' => [
      'label' => 'Selection', 
      'type' => 'select', 
      'description' => 'The list of devices that the browser agent must match. Use the shift or ctrl/cmd key to select multiple options.', 
      'source' => true, 
      'attrs' => [
        'multiple' => true
      ], 
      'options' => [
        'Mobile' => 'mobile', 
        'Tablet' => 'tablet', 
        'Desktop' => 'desktop'
      ]
    ], 
    '_notice' => [
      'type' => 'yooessentials-info', 
      'content' => 'Keep in mind that device detection is not always accurate, users can setup their browser to mimic other agents.'
    ]
  ]
];
