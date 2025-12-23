<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/core-storage-adapters/FTP/config-sftp.json

return [
  'name' => 'sftp', 
  'title' => 'SFTP', 
  'description' => 'SFTP protocol.', 
  'collection' => 'FTP', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/core-storage-adapters/FTP/icon.svg', $file), 
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
    '_host' => [
      'type' => 'grid', 
      'width' => '1-2', 
      'fields' => [
        'host' => [
          'label' => 'Host', 
          'type' => 'text'
        ], 
        'port' => [
          'label' => 'Port', 
          'type' => 'text', 
          'attrs' => [
            'placeholder' => '22'
          ]
        ]
      ]
    ], 
    '_creds' => [
      'description' => 'The Host, Port and credentials for establishing the connection.', 
      'type' => 'grid', 
      'width' => '1-2', 
      'fields' => [
        'username' => [
          'label' => 'Username', 
          'type' => 'text'
        ], 
        'password' => [
          'label' => 'Password', 
          'type' => 'yooessentials-secret', 
          'encrypt' => true
        ]
      ]
    ], 
    '_creds_key' => [
      'description' => 'Credentials to use when using a private key', 
      'type' => 'grid', 
      'width' => '1-2', 
      'fields' => [
        'privateKey' => [
          'label' => 'Private Key', 
          'type' => 'yooessentials-file'
        ], 
        'passphrase' => [
          'label' => 'Passphrase', 
          'type' => 'yooessentials-secret', 
          'encrypt' => true
        ]
      ]
    ], 
    'root' => [
      'label' => 'Root', 
      'type' => 'text', 
      'attrs' => [
        'placeholder' => '/'
      ], 
      'description' => 'The remote path that will be used as the storage root.'
    ], 
    'writable' => [
      'label' => 'Write Permission', 
      'text' => 'Grant write permissions for this storage.', 
      'type' => 'checkbox'
    ]
  ]
];
