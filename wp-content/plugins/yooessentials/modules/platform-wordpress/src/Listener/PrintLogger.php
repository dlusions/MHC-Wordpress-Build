<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Wordpress\Listener;

use YOOtheme\Config;
use ZOOlanders\YOOessentials\Debug\Logger;

class PrintLogger
{
    public Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function handle(): void
    {
        if ($this->config->get('app.isCustomizer')) {
            echo sprintf('<script>window.$yooesslogs = %s</script>', json_encode(Logger::$loggers));
        }
    }
}
