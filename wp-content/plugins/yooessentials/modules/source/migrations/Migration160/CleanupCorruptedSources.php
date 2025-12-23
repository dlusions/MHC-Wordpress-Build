<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration160;

use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Util;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

// migrate sources to new format
class CleanupCorruptedSources extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '1.6.0';

    public function migrateConfig(array $config): array
    {
        // filter invalid sources
        Util\Config::filter($config, 'source.sources', fn ($source) => array_key_exists('provider', $source));

        // cleanup corrupted sources (some weird issue we had)
        Util\Config::iterate($config, 'source.sources', function (&$source) {
            $source = array_filter(
                $source,
                fn ($key) => !is_int($key),
                ARRAY_FILTER_USE_KEY
            );
        });

        return $config;
    }
}
