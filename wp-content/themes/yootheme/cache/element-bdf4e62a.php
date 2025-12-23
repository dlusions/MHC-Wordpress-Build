<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/fs-switcher/modules/element/switcher_item/sl/element.json

return [
  '@import' => $filter->apply('path', './element.php', $file), 
  'name' => 'fs_switcher_sl_item', 
  'title' => 'Item', 
  'width' => 500, 
  'fragment' => true, 
  'placeholder' => [
    'props' => [
      'title' => 'Title', 
      'image' => ''
    ]
  ], 
  'templates' => [
    'render' => $filter->apply('path', './templates/template.php', $file), 
    'content' => $filter->apply('path', './templates/content.php', $file)
  ], 
  'fields' => [
    'content' => [
      'type' => 'builder-fragment', 
      'divider' => true
    ], 
    'title' => [
      'label' => 'Title', 
      'description' => 'Navigation Label.', 
      'source' => true
    ], 
    'tab_link' => [
      'label' => 'Link', 
      'description' => 'Custom navigation link.', 
      'source' => true
    ], 
    'image' => [
      'label' => 'Thumbnail', 
      'description' => 'This is only used, if the thumbnail navigation is set.', 
      'type' => 'image', 
      'source' => true, 
      'enable' => '!thumbnail_icon'
    ], 
    'thumbnail_hover' => [
      'label' => 'Thumbnail Hover', 
      'description' => 'This is only used, if the thumbnail navigation is set.', 
      'type' => 'image', 
      'source' => true, 
      'enable' => '!thumbnail_icon'
    ], 
    'thumbnail_icon' => [
      'label' => 'Icon', 
      'description' => 'Instead of using a navigation thumbnail image, you can click on the pencil to pick an icon from the icon library.', 
      'type' => 'icon', 
      'source' => true, 
      'enable' => '!image'
    ], 
    'item_element' => $config->get('builder.html_element_item'), 
    'switcher_thumbnail_focal_point' => $config->get('fs_switcher.focal_point'), 
    'switcher_thumbnail_text_color' => $config->get('fs_switcher.text_color'), 
    'name' => $config->get('builder.name'), 
    'status' => $config->get('builder.statusItem'), 
    'source' => $config->get('builder.source'), 
    'id' => $config->get('builder.id'), 
    'class' => $config->get('builder.cls'), 
    'attributes' => $config->get('builder.attrs')
  ], 
  'fieldset' => [
    'default' => [
      'type' => 'tabs', 
      'fields' => [[
          'title' => 'Content', 
          'fields' => ['content', 'title', 'tab_link', [
              'name' => '_switcher_sl_thumbnail', 
              'type' => 'fields', 
              'fields' => ['image', 'thumbnail_hover', 'thumbnail_icon'], 
              'show' => 'this.builder.parent(this.node)[\'props\'][\'switcher_style\'] == \'thumbnav\' || (this.builder.parent(this.node)[\'props\'][\'switcher_style\'] == \'accordion\' && this.builder.parent(this.node)[\'props\'][\'switcher_accordion_thumbnail\'])'
            ]]
        ], [
          'title' => 'Settings', 
          'fields' => [[
              'label' => 'Item', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['item_element']
            ], [
              'label' => 'Thumbnail', 
              'type' => 'group', 
              'divider' => true, 
              'fields' => ['switcher_thumbnail_focal_point', 'switcher_thumbnail_text_color'], 
              'show' => 'this.builder.parent(this.node)[\'props\'][\'switcher_style\'] == \'thumbnav\' || (this.builder.parent(this.node)[\'props\'][\'switcher_style\'] == \'accordion\' && this.builder.parent(this.node)[\'props\'][\'switcher_accordion_thumbnail\'])'
            ]]
        ], [
          'title' => 'Advanced', 
          'fields' => ['name', 'status', 'source', 'id', 'class', 'attributes']
        ]]
    ]
  ]
];
