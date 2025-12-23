<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('WPINC') or die();

abstract class YooessentialsSettings
{
    public static function settings()
    {
        register_setting('yooessentials', 'zoolanders_download_id');
        register_setting('yooessentials', 'yooessentials_min_stability');
    }

    public static function settingsMenu()
    {
        add_options_page('ZOOlanders', 'ZOOlanders', 'manage_options', 'yooessentials', [
            YooessentialsSettings::class,
            'settingsPage',
        ]);
    }

    public static function settingsLink(array $links)
    {
        $url = esc_url(add_query_arg('page', 'yooessentials', get_admin_url() . 'options-general.php'));
        $settings_link = "<a href='$url'>" . __('Settings') . '</a>';
        $links[] = $settings_link;

        return $links;
    }

    public static function settingsPage()
    {
        ?>
        <div class="wrap">
            <h1>Essential Addons for YOOtheme Pro</h1>

            <form method="post" action="options.php">
                <?php settings_fields('yooessentials'); ?>
                <?php do_settings_sections('yooessentials'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Download ID</th>
                        <td><input type="password" name="zoolanders_download_id" value="<?php echo esc_attr(
                            get_option('zoolanders_download_id')
                        ); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Minimum Stability</th>
                        <td>
                            <select name="yooessentials_min_stability">
                                <option value="stable" <?php if (
                                    get_option('yooessentials_min_stability', 'stable') === 'stable'
                                ): ?>selected="selected"<?php endif; ?>>Stable</option>
                                <option value="rc" <?php if (
                                    get_option('yooessentials_min_stability', 'stable') === 'rc'
                                ): ?>selected="selected"<?php endif; ?>>RC</option>
                                <option value="beta" <?php if (
                                    get_option('yooessentials_min_stability', 'stable') === 'beta'
                                ): ?>selected="selected"<?php endif; ?>>Beta</option>
                                <option value="alpha" <?php if (
                                    get_option('yooessentials_min_stability', 'stable') === 'alpha'
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
}
