<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/form-elements/form/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'yooessentials_form', 
  'title' => 'Form', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form/images/icon.svg', $file), 
  'iconSmall' => $filter->apply('url', '~yooessentials_url/modules/form-elements/form/images/iconSmall.svg', $file), 
  'container' => true, 
  'width' => 500, 
  'defaults' => [], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file)
  ]
];
