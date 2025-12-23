<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/core-storage-adapters/S3/config.json

return [
  'name' => 's3', 
  'title' => 'Amazon S3', 
  'description' => 'Create a storage from an Amazon S3 Bucket.', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/core-storage-adapters/S3/icon.svg', $file), 
  'endpoints' => [
    'presave' => 'yooessentials/storage/adapter/presave'
  ], 
  'fields' => [
    'name' => [
      'label' => 'Name', 
      'description' => 'A name to identify this storage.', 
      'attrs' => [
        'autofocus' => true
      ]
    ], 
    'account' => [
      'label' => 'Account', 
      'type' => 'yooessentials-connected-auth', 
      'connections' => [
        'aws' => []
      ], 
      'description' => 'The Amazon AWS account with which to access the resources.'
    ], 
    '_bucket' => [
      'description' => 'The Amazon S3 Bucket name and it AWS Region, e.g. <i>eu-west-1</i>.', 
      'type' => 'grid', 
      'width' => '1-2', 
      'fields' => [
        'bucket' => [
          'label' => 'Bucket', 
          'type' => 'text'
        ], 
        'region' => [
          'label' => 'Region', 
          'type' => 'text'
        ]
      ]
    ], 
    'root' => [
      'label' => 'Root', 
      'type' => 'text', 
      'attrs' => [
        'placeholder' => '/'
      ], 
      'description' => 'The default remote path that will be used as the storage root, e.g. <code>/layouts</code>.'
    ], 
    'writable' => [
      'label' => 'Write Permission', 
      'text' => 'Grant write permissions for this storage.', 
      'type' => 'checkbox'
    ]
  ]
];
