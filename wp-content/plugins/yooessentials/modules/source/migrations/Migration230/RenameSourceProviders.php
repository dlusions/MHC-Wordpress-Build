<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration230;

use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Util;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class RenameSourceProviders extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.3.0';

    public function migrateConfig(array $config): array
    {
        Util\Config::iterate($config, 'source.sources', function (&$source) {
            $provider = $source['provider'] ?? '';

            if ($provider === 'google_mybusiness' || $provider === 'google-mybusiness') {
                $source['provider'] = 'google-business-profile';
            }

            if ($provider === 'google_calendar') {
                $source['provider'] = 'google-calendar';
            }

            if ($provider === 'google_photos') {
                $source['provider'] = 'google-photos';
            }

            if ($provider === 'google-sheet' || $provider === 'google_sheet') {
                $source['provider'] = 'google-sheets';
            }

            if ($provider === 'youtube_playlist') {
                $source['provider'] = 'youtube-playlist';
            }

            if ($provider === 'youtube_channel') {
                $source['provider'] = 'youtube-channel';
            }
        });

        return $config;
    }
}
