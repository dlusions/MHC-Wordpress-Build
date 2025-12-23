<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/element/elements/social_sharing_item/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'yooessentials_social_sharing_item', 
  'title' => 'Network', 
  'iconSmall' => $filter->apply('url', '~yooessentials_url/modules/element/elements/social_sharing/images/iconSmall.svg', $file), 
  'width' => 500, 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file), 
    'content' => $filter->apply('path', './templates/content.php', $file)
  ], 
  'defaults' => [
    'link_target' => '_blank', 
    'link_target_width' => '', 
    'link_target_height' => ''
  ], 
  'fields' => [
    'network' => [
      'label' => 'Share To', 
      'type' => 'select', 
      'default' => 'x', 
      'options' => [
        'Bluesky' => 'bluesky', 
        'Facebook' => 'facebook', 
        'LinkedIn' => 'linkedin', 
        'Mastodon' => 'mastodon', 
        'Pinterest' => 'pinterest', 
        'Telegram' => 'telegram', 
        'WhatsApp' => 'whatsapp', 
        'X' => 'x', 
        'Xing' => 'xing', 
        'Custom' => 'custom'
      ]
    ], 
    'custom_link' => [
      'label' => 'Share To Link', 
      'attrs' => [
        'placeholder' => 'http://mysharer.com/?url={URL}&text={TEXT}'
      ], 
      'description' => 'Set a custom share link with <code>{URL}</code> and <code>{TEXT}</code> as Shared URL and Text placeholders.', 
      'show' => 'network === \'custom\''
    ], 
    'shared_url' => [
      'label' => 'Shared URL', 
      'description' => 'The URL to share, defaults to the current page.', 
      'source' => true
    ], 
    'shared_text' => [
      'label' => 'Shared Text', 
      'description' => 'An optional text to share alongside the URL. Use <code>{URL}</code> placeholder to reference the sharing url.', 
      'show' => 'network.match(/x|telegram|whatsapp|bluesky|custom/)', 
      'source' => true
    ], 
    'link_title' => [
      'label' => 'Link Title', 
      'description' => 'An optional title for the link.', 
      'source' => true
    ], 
    'link_target' => [
      'label' => 'Link Target', 
      'description' => 'Set the target window for the sharing links to open.', 
      'type' => 'select', 
      'options' => [
        'New Window' => '_blank', 
        'Same Window' => '_self', 
        'PopUp Window' => 'popup'
      ]
    ], 
    'link_target_width' => [
      'label' => 'Width', 
      'attrs' => [
        'placeholder' => 600
      ]
    ], 
    'link_target_height' => [
      'label' => 'Height', 
      'attrs' => [
        'placeholder' => 600
      ]
    ], 
    'link_aria_label' => [
      'label' => 'Link ARIA Label', 
      'description' => 'Set a different link ARIA label for this item.', 
      'source' => true
    ], 
    'icon' => [
      'label' => 'Icon', 
      'description' => 'Leave empty for the default icon or pick a custom one from the icon library.', 
      'type' => 'icon', 
      'source' => true, 
      'enable' => '!image'
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
    'attributes' => $config->get('builder.attrs')
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Content', 
          'fields' => ['network', 'custom_link', 'shared_url', 'shared_text', 'link_title', 'link_target', [
              'description' => 'The target window specifications.', 
              'name' => '_link_target', 
              'type' => 'grid', 
              'width' => '1-2', 
              'fields' => ['link_target_width', 'link_target_height'], 
              'show' => 'link_target === \'popup\''
            ], 'link_aria_label', 'icon', 'image']
        ], $config->get('builder.advanced')]
    ]
  ]
];
