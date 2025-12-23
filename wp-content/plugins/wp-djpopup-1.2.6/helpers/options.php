<?php

require_once(dirname(__DIR__ ) . '/includes/cmb2_license.php');

add_action( 'cmb2_admin_init', 'djpopup_register_options_metabox' );
/**
 * Hook in and register a metabox to handle a plugin options page and adds a menu item.
 */
function djpopup_register_options_metabox() {

	/**
	 * Registers options page menu item and form.
	 */

	$cmb = new_cmb2_box( array(
		'id'           => 'djpopup_options',
		'title'        => 'DJ-Popup',
		'object_types' => array( 'options-page' ),

		'option_key'      => 'djpopup_options', // The option key and admin menu page slug.
		'icon_url'        => 'dashicons-format-aside', // Menu icon. Only applicable if 'parent_slug' is left empty.
		'menu_title'      => esc_html__( 'DJ-Popup', 'djpopup' ), // Falls back to 'title' (above).
		// 'parent_slug'     => 'themes.php', // Make options page a submenu item of the themes menu.
		// 'capability'      => 'manage_options', // Cap required to view options-page.
		// 'position'        => 1, // Menu position. Only applicable if 'parent_slug' is left empty.
		// 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
		// 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
		// 'save_button'     => esc_html__( 'Save Theme Options', 'cmb2' ), // The text for the options-page save button. Defaults to 'Save'.
		// 'disable_settings_errors' => true, // On settings pages (not options-general.php sub-pages), allows disabling.
		// 'message_cb'      => 'djpopup_options_page_message_callback',
	) );

    $cmb->add_field( array(
        'name'             => 'License key',
        'desc'             => '',
        'id'               => 'djpopup_dlid',
        'type'             => 'djpopuplicense',
    ) );


}

function djpopup_sanitize_checkbox($value, $field_args, $field) {
	// Return 0 instead of false if null value given.
	return is_null($value) ? 0 : $value;
}

?>