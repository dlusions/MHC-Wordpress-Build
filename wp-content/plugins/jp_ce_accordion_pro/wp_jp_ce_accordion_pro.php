<?php
/*
Plugin Name: JP CE Accordion Pro
Plugin URI: https://jpro.studio/product/jp-ce-accordion-pro
Description: JPro Custom Element Accordion Pro is downloaded from jpro.studio
Version: 1.1.3
Author: JproStudio
Author URI: https://jpro.studio
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Update URI: https://jpro.studio/index.php?option=com_ochsubscriptions&amp;view=updater&amp;format=xml&amp;element=jp_ce_accordion_pro_wordpress
*/

use YOOtheme\Application;
use YOOtheme\Path;



class jp_ce_accordion_pro {
	
	function __construct() {
		add_action('wp_loaded', array( $this, 'jp_ce_accordion_pro_load' ));
     }
	 
	 function jp_ce_accordion_pro_load(){
	 
	 	if (!class_exists(Application::class, false)) {
            return;
        }
		
		
		$application = Application::getInstance();
		$application->load(dirname(__FILE__).'/elements/uikit.php');
		

		add_action('admin_init', [$this, 'settings']);
		add_action('admin_menu', array( $this, 'jpro_menu' ));
		add_filter('plugin_action_links_jp_ce_accordion_pro/wp_jp_ce_accordion_pro.php', [$this, 'jpro_link']);
		
		add_filter('pre_set_site_transient_update_plugins', [$this, 'jpro_update'], 10, 2);
		add_filter('after_plugin_row_jp_ce_accordion_pro/wp_jp_ce_accordion_pro.php', [$this, 'errorMessage'], 10, 3);
		
		
	 }
	 
	public static function jpro_menu()
    {
		global $submenu;
		$added = 0;
		
		if (!empty($submenu["options-general.php"])) 
			foreach ($submenu["options-general.php"] as $jpsubmenu) 
				if ($jpsubmenu[0] == "JproStudio")  $added = 1;
		
		if (!$added) add_options_page('JproStudio', 'JproStudio', 'manage_options', 'jpro', array( jp_ce_accordion_pro::class, 'jpro_settings' ));
		
    }
	
	public static function settings()
    {
		
        register_setting('jpro', 'jpro_download_id');
        register_setting('jpro', 'jpro_min_stability');
    }
	
	public static function jpro_settings()
    {
		
        ?>
        <div class="wrap">
            <h1>Settings</h1>

            <form method="post" action="options.php">
                <?php settings_fields('jpro'); ?>
                <?php do_settings_sections('jpro'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Download ID</th>
                        <td><input type="password" name="jpro_download_id" value="<?php echo esc_attr(
            get_option('jpro_download_id')
        ); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Minimum Stability</th>
                        <td>
                            <select name="jpro_min_stability">
                                <option value="stable" <?php if (
                                    get_option('jpro_min_stability', 'stable') === 'stable'
                                ): ?>selected="selected"<?php endif; ?>>Stable</option>
                                <option value="rc" <?php if (
                                    get_option('jpro_min_stability', 'stable') === 'rc'
                                ): ?>selected="selected"<?php endif; ?>>RC</option>
                                <option value="beta" <?php if (
                                    get_option('jpro_min_stability', 'stable') === 'beta'
                                ): ?>selected="selected"<?php endif; ?>>Beta</option>
                                <option value="alpha" <?php if (
                                    get_option('jpro_min_stability', 'stable') === 'alpha'
                                ): ?>selected="selected"<?php endif; ?>>Alpha</option>
                            </select>
                        </td>
                    </tr>
                </table>

                <?php submit_button(); ?>

            </form>
        </div>
    <?php
    }
	
	public static function jpro_link(array $links)
    {
		
        $url = esc_url(add_query_arg('page', 'jpro', get_admin_url() . 'options-general.php'));
        $settings_link = "<a href='$url'>" . __('Settings') . '</a>';
        array_push($links, $settings_link);
        return $links;
    }
	
	public  function jpro_update($transient)
    {
		
		$plugin_data = get_plugin_data( __FILE__ );
		$downloadId = get_option('jpro_download_id');
		
		if (empty($plugin_data["UpdateURI"])) return;
		
		$download = (new WP_Http())->get($plugin_data["UpdateURI"]);
		if (is_wp_error($download) or !($download['body'] ?? false)) {
           return $transient; 
    	}
		
		try {
            $updateInfo = simplexml_load_string($download['body']);
        } catch (\Exception $e) {
            return $transient; 
        }
		
		$update = $updateInfo->update;
		

		$plugin_version = $plugin_data["Version"];
		$current_version = $update->version;

		
		$hasUpdate	= version_compare($current_version, $plugin_version, 'gt');
		if (empty($hasUpdate)) return $transient; 
		
		$link = (string) $update->downloads->downloadurl;
		if (empty($link)) return $transient; 
		
		if (!empty($downloadId)) {
			$link .= "&key=".$downloadId;
		}
		
		$link = trim($link);
		$obj = new stdClass();
        $obj->slug = 'jp_ce_accordion_pro';
        $obj->plugin = 'jp_ce_accordion_pro/wp_jp_ce_accordion_pro.php';
		$obj->new_version = (string)$current_version;
        $obj->url = (string)$update->changelogurl;
        $obj->package = $link;

        $transient->response['jp_ce_accordion_pro/wp_jp_ce_accordion_pro.php'] = $obj;
        return $transient;
		
    }
	 
	public static function errorMessage($plugin_file, $plugin_data, $status)
    {
		
     	if ( !get_option('jpro_download_id')) {
          
            $warnings = '<p>The Download ID has not been set. You can do in the Settings::JproStudio </p>';
            $msg = 'Warning:';

            $html = <<<HTML
<tr class="">
	<th></th>
	<td></td>
	<td>
		<div style="border: 1px solid #F0AD4E;border-radius: 3px;background: #fdf5e9;padding:10px">
			<strong>$msg</strong><br/>
			$warnings
		</div>
	</td>
</tr>
HTML;
		echo $html;
        }
    }
	
}
new jp_ce_accordion_pro();

function register_accordion_session(){
	
    if( !session_id() )
	{
       session_start([
'read_and_close' => true,
]);
	}
	
}
 

function jp_accordion_save() {

    if ( isset($_REQUEST) ) {
		
		$id = $_REQUEST['id'];
		$active = $_REQUEST['active'];
		$_SESSION[$id] = $active;
		$return = array();
	    $return['success'] = 1;
	    echo json_encode($return);

    }
   die();
}

add_action('init','register_accordion_session');
add_action( 'wp_ajax_jp_accordion_save', 'jp_accordion_save' ); 
add_action( 'wp_ajax_nopriv_jp_accordion_save', 'jp_accordion_save' );
