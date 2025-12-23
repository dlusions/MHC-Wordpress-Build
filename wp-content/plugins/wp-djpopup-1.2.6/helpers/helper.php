<?php
use YOOtheme\Application;
use function YOOtheme\app;

class DJAcc {

	public static function getName() {
		$plugin_type = self::pluginType();
		$subtitle = ( $plugin_type ) ? 'Pro' : 'Light';
		$subtitle .= ( !$plugin_type ) ? ' <a class="button button-secondary" href="https://dj-extensions.com/yootheme/dj-accessibility">Get Pro</a>' : '';
		return esc_html__( 'DJ-Accessibility', 'djpopup' ) . ' ' . $subtitle;
	}

	public static function checkTheme() {
		$t = wp_get_theme();
		return ( is_object($t) ) ? $t['Name'] : false;
	}

	public static function checkYootheme() {
		return ( 'YOOtheme' === self::checkTheme() ) ? true : false;
	}

	public static function getFile( $file, &$params = null ) {
		// Start capturing output into a buffer
		ob_start();

		// Include the requested template filename in the local scope
		// (this will execute the view logic).
		include $file;

		// Done with the requested template; get the buffer and
		// clear it.
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	public static function getOption( $key = '', $default = false ) {
		if ( function_exists( 'cmb2_get_option' ) ) {
			// Use cmb2_get_option as it passes through some key filters.
			return cmb2_get_option( 'djpopup_options', $key, $default );
		}
	
		// Fallback to get_option if CMB2 is not loaded yet.
		$opts = get_option( 'djpopup_options', $default );
	
		$val = $default;
	
		if ( 'all' == $key ) {
			$val = $opts;
		} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
			$val = $opts[ $key ];
		}
	
		return $val;
	}

	public static function getParam( $param, $default = null ) {
		$param = str_replace('djpopup_', '', $param);
		
		$field_wp = self::getOption('djpopup_' . $param, $default);

		//fields priority: Yootheme > WP > default

		if( DJACC_YOOTHEME ) {
			return app()->config->get('~theme.djpopup_' . $param, $field_wp);
		} else {
			return $field_wp;
		}
	}

	public static function getLayout( $layout ) {
		$file = $layout . '.php';
		$plugin_path = DJACC_PATH . '/tmpl/' . $file;
		$theme_path = get_template_directory() . '/djpopup/' . $file;
		if( file_exists($theme_path) ) {
			return self::getFile($theme_path);
		} else {
			return self::getFile($plugin_path);
		}
	}

	public static function pluginType() {
		return ( class_exists('DJAccPro') ) ? DJAccPro::getVersion() : '';
	}

	public static function saveDID($dlid) {
		$opt = get_option( 'djpopup_options' );
		$opt['djpopup_dlid'] = array('key' => $dlid);
		update_option( 'djpopup_options', $opt );
	}

	public static function getDID() {
		$opt = get_option( 'djpopup_options' );
		return ( !empty($opt['djpopup_dlid']) ) ? $opt['djpopup_dlid']['key'] : false;
	}

}

?>