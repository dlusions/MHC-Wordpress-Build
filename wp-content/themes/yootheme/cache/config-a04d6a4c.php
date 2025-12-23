<?php // $file = /nas/content/live/mhc2025dev/wp-content/plugins/yooessentials/modules/core-condition-rules/Geolocation/config.json

return [
  'name' => 'yooessentials_access_ipgeolocation', 
  'title' => 'Geolocation', 
  'group' => '', 
  'description' => 'Validates against the device IP location determined by MaxMind GeoIp.', 
  'icon' => $filter->apply('url', '~yooessentials_url/modules/core-condition-rules/Geolocation/icon.svg', $file), 
  'fields' => [
    'continents' => [
      'label' => 'Continents', 
      'type' => 'textarea', 
      'source' => true, 
      'attrs' => [
        'rows' => 4, 
        'placeholder' => 'South America
Africa
AU'
      ], 
      'show' => 'yooessentials.customizer.geoipcountry || yooessentials.customizer.geoipcity', 
      'description' => 'The list of Continents (names or 2 digit iso codes) that the device IP location must match, international names are supported. Separate the entries with a comma and/or new line.'
    ], 
    '_continents' => [
      'label' => 'Continents', 
      'type' => 'yooessentials-info', 
      'show' => '!(yooessentials.customizer.geoipcountry || yooessentials.customizer.geoipcity)', 
      'description' => 'This feature relies on the GeoIp City or Country Database.'
    ], 
    'countries' => [
      'label' => 'Countries', 
      'type' => 'textarea', 
      'source' => true, 
      'attrs' => [
        'rows' => 4, 
        'placeholder' => 'Spain
Italy
DE'
      ], 
      'show' => 'yooessentials.customizer.geoipcountry || yooessentials.customizer.geoipcity', 
      'description' => 'The list of Countries (names or 2 digit iso codes) that the device IP location must match, international names are supported. Separate the entries with a comma and/or new line.'
    ], 
    '_countries' => [
      'label' => 'Countries', 
      'type' => 'yooessentials-info', 
      'show' => '!(yooessentials.customizer.geoipcountry || yooessentials.customizer.geoipcity)', 
      'description' => 'This feature relies on the GeoIp City or Country Database.'
    ], 
    'cities' => [
      'label' => 'Cities', 
      'type' => 'textarea', 
      'source' => true, 
      'attrs' => [
        'rows' => 4, 
        'placeholder' => 'Barcelona
Vicenza
Denpasar'
      ], 
      'show' => 'yooessentials.customizer.geoipcity', 
      'description' => 'The list of Cities that the device IP location must match, international names are supported. Separate the entries with a comma and/or new line.'
    ], 
    '_cities' => [
      'label' => 'Cities', 
      'type' => 'yooessentials-info', 
      'show' => '!yooessentials.customizer.geoipcity', 
      'description' => 'This feature relies on the GeoIp City Database.'
    ], 
    'codes' => [
      'label' => 'Postal Codes', 
      'type' => 'textarea', 
      'source' => true, 
      'attrs' => [
        'rows' => 4, 
        'placeholder' => '55455'
      ], 
      'show' => 'yooessentials.customizer.geoipcity', 
      'description' => 'The list of Postal Codes that the device IP location must match. Separate the entries with a comma and/or new line.'
    ], 
    '_codes' => [
      'label' => 'Postal Codes', 
      'type' => 'yooessentials-info', 
      'show' => '!yooessentials.customizer.geoipcity', 
      'description' => 'This feature relies on the GeoIp City Database.'
    ]
  ]
];
