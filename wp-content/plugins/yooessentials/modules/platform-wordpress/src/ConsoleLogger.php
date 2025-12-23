<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Wordpress;

use YOOtheme\Metadata;

class ConsoleLogger extends \ZOOlanders\YOOessentials\Debug\ConsoleLogger
{
    public static function print(Metadata $metadata)
    {
        if (empty(self::$LOGS)) {
            return;
        }

        $script = self::buildPrintScript(self::$LOGS);
        echo sprintf('<script>%s</script>', $script);
    }

    public static function alert(Metadata $metadata)
    {
        $errors = array_filter(self::$LOGS, fn ($log) => $log['type'] === 'error');

        if (empty($errors)) {
            return;
        }

        $script = self::buildAlertScripts($errors);
        echo sprintf('<script>%s</script>', $script);
    }
}
