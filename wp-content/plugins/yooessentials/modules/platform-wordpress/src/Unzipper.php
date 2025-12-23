<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Wordpress;

use YOOtheme\Event;
use ZOOlanders\YOOessentials\Unzipper as UnzipperInterface;

class Unzipper implements UnzipperInterface
{
    public function unzip(string $file, string $dest): bool
    {
        \WP_Filesystem();
        $result = \unzip_file($file, $dest);

        if ($result === true) {
            return true;
        }
        /** @var \WP_Error $result */
        Event::emit('yooessentials.error', [
            'addon' => 'core',
            'action' => 'unzip',
            'file' => $file,
            'destination' => $dest,
            'error' => $result->get_error_message(),
        ]);

        return false;
    }
}
