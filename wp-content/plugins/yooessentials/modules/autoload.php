<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

$class_map = array_merge(
    // Core ZOOlanders classes
    include __DIR__ . '/vendor/composer/autoload_classmap.php',
    // Third-party classes.
    include __DIR__ . '/vendor/vendor/composer/autoload_classmap.php'
);

spl_autoload_register(
    function ($class) use ($class_map) {
        if (isset($class_map[$class]) && file_exists($class_map[$class])) {
            require_once $class_map[$class];

            return true;
        }
    },
    true,
    true
);

// Third-party files.
$files = require __DIR__ . '/vendor/vendor/autoload_files.php';
foreach ($files as $file_identifier => $file) {
    if (file_exists($file)) {
        require_once $file;
    }
}

// Fix YOOtheme changing Graphql class paths.
$graphqlTypes = ['ObjectType', 'IntType'];

foreach ($graphqlTypes as $type) {
    $originalClass = "\\GraphQL\\Type\\Definition\\$type";
    $yooClass = "\\YOOtheme\\GraphQL\\Type\\Definition\\$type";

    if (!class_exists($yooClass)) {
        class_alias($originalClass, $yooClass);
    }
}

// Files
require_once __DIR__ .'/core/functions.php';
