<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Plugin Name: DJ-Popup
 * Plugin URI: https://dj-extensions.com/yootheme/products/dj-flipbook
 * Description: If you need to present your PDFs fancy and comfy way
 * Version: 1.2.6
 * Author: DJ-Extensions.com
 * Author URI: https://dj-extensions.com
 * Text Domain: dj-sectionsanywhere
 * License: https://dj-extensions.com/license.html DJ-Extensions Proprietary Use License
 */

use YOOtheme\Application;
use YOOtheme\Path;

if ( !class_exists ( 'DJPopup' ) ) {


    class DJPopup {
        function __construct() {
            $t = wp_get_theme();
            if($t['Template'] != 'yootheme') return;

            add_action( 'after_setup_theme', array( $this, 'initModule' ) );
            add_action( 'plugins_loaded', array( $this, 'init' ) );



        }

        public function init() {
            if( is_admin() ) {
                require __DIR__ . '/includes/cmb2/init.php';
                require __DIR__ . '/helpers/options.php';
            }
        }

        function initModule() {

            // Check if YOOtheme Pro is loaded
            if (!class_exists(Application::class, false)) {
                return;
            }

            $app = Application::getInstance();

            $root = __DIR__;
            $rootUrl = plugin_dir_url( __FILE__ );

            // set alias
            Path::setAlias('~djpopup', $root);
            Path::setAlias('~djpopup_url', $rootUrl);

            require_once __DIR__ . '/vendor/autoload.php';

            // bootstrap modules
            $app->load('~djpopup/modules/builder/bootstrap.php');
        }

    }
}

new DJPopup();