<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Wordpress;

class WpDb extends \wpdb
{
    public function __construct($dbuser, $dbpassword, $dbname, $dbhost)
    {
        $this->hide_errors();

        // Use the `mysqli` extension if it exists unless `WP_USE_EXT_MYSQL` is defined as true.
        if (function_exists('mysqli_connect')) {
            $this->use_mysqli = true;

            if (defined('WP_USE_EXT_MYSQL')) {
                $this->use_mysqli = !WP_USE_EXT_MYSQL;
            }
        }

        $this->dbuser = $dbuser;
        $this->dbpassword = $dbpassword;
        $this->dbname = $dbname;
        $this->dbhost = $dbhost;
    }
}
