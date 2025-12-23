<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration180;

use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Util;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class MigrateSourceProviders extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '1.8.8';

    public function migrateConfig(array $config): array
    {
        Util\Config::iterate($config, 'source.sources', function (&$source) {
            $videosSource = $source['videos_source'] ?? '';

            // youtube source is divided into channel and playlist source
            if ($videosSource === 'channel') {
                $source['provider'] = 'youtube_channel';
                unset($source['videos_source']);
            }

            if ($videosSource === 'playlist') {
                $source['provider'] = 'youtube_playlist';
                unset($source['videos_source']);
            }

            // Instagram source is divided into personal and business source
            if ($source['provider'] === 'instagram' && isset($source['page_id'])) {
                $source['provider'] = 'instagram_business';
            }
        });

        return $config;
    }
}
