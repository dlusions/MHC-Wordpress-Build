<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/element/elements/social_sharing_viber/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'yooessentials_social_sharing_viber', 
  'title' => 'Viber', 
  'iconSmall' => $filter->apply('url', '~yooessentials_url/modules/element/elements/social_sharing_viber/icon.svg', $file), 
  'width' => 500, 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file), 
    'content' => $filter->apply('path', './templates/content.php', $file)
  ], 
  'fields' => [
    'shared_url' => [
      'label' => 'Shared URL', 
      'description' => 'The URL to share, defaults to the current page.', 
      'source' => true
    ], 
    'shared_text' => [
      'label' => 'Shared Text', 
      'description' => 'An optional text to share alongside the URL. Use <code>{URL}</code> placeholder to reference the sharing url.', 
      'source' => true
    ], 
    'link_title' => [
      'label' => 'Link Title', 
      'description' => 'Optionally set the title for the link.'
    ], 
    'link_aria_label' => [
      'label' => 'Link ARIA Label', 
      'description' => 'Set a different link ARIA label for this item.', 
      'source' => true
    ], 
    'icon' => [
      'label' => 'Icon', 
      'description' => 'Choose an icon from the icon library.', 
      'type' => 'icon', 
      'source' => true
    ], 
    'image' => [
      'label' => 'Image', 
      'description' => 'Pick an alternative SVG image from the media manager.', 
      'type' => 'image', 
      'source' => true, 
      'enable' => '!icon'
    ], 
    'status' => $config->get('builder.status'), 
    'id' => $config->get('builder.id'), 
    'class' => $config->get('builder.cls'), 
    'attributes' => $config->get('builder.attrs'), 
    'source' => $config->get('builder.source')
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Content', 
          'fields' => ['shared_text', 'link_title', 'link_aria_label', 'icon', 'image']
        ], $config->get('builder.advanced')]
    ]
  ]
];
