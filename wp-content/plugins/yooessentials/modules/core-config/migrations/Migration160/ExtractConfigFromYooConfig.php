<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration160;

use function YOOtheme\app;
use YOOtheme\Arr;
use YOOtheme\Config as Yooconfig;
use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

// migrate config data out from yooconfig
class ExtractConfigFromYooConfig extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '1.6.0';

    public function migrateConfig(array $config): array
    {
        /** @var Yooconfig $yooconfig */
        $yooconfig = app(Yooconfig::class);

        foreach (['access', 'auth', 'element', 'form', 'icon', 'source'] as $path) {
            if ($val = $yooconfig->get("~theme.yooessentials.{$path}")) {
                if (!Arr::has($config, $path)) {
                    Arr::set($config, $path, $val);
                }

                $yooconfig->del("~theme.yooessentials.{$path}");
            }
        }

        $yooconfig->del('~theme.yooessentials');

        return $config;
    }
}
