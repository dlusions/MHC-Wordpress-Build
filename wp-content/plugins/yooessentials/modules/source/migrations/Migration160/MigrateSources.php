<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration160;

use YOOtheme\Str;
use YOOtheme\File;
use ZOOlanders\YOOessentials\Source\Provider\Csv\CsvSource;
use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Util;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

// Add ID if source created before id standard
class MigrateSources extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '1.6.0';

    public function migrateConfig(array $config): array
    {
        Util\Config::iterate($config, 'source.sources', function (&$source) {
            $provider = $source['provider'] ?? '';

            if ($provider === 'csv') {
                self::migrateCsvSource($source);
            }

            if ($provider === 'google_sheet') {
                self::migrateGoogleSheetSource($source);
            }

            if ($provider === 'instagram') {
                self::migrateInstagramSource($source);
            }
        });

        return $config;
    }

    protected static function migrateInstagramSource(&$source): void
    {
        if (isset($source['id'])) {
            return;
        }

        $source['id'] = ($source['user_id'] ?? '') . ($source['page_id'] ?? '');

        if (isset($source['user_id'])) {
            $source['account'] = $source['user_id'];
            unset($source['user_id']);
        }
    }

    protected static function migrateGoogleSheetSource(&$source): void
    {
        if (isset($source['id'])) {
            return;
        }

        $source['id'] = $source['sheet_id'] ?? $source['id'];

        if (isset($source['user_id'])) {
            $source['account'] = $source['user_id'];
            unset($source['user_id']);
        }
    }

    protected static function migrateCsvSource(&$source): void
    {
        if (isset($source['id'])) {
            return;
        }

        $sourceInstance = new CsvSource($source);
        $file = $sourceInstance->config('file');

        if ($file and !Str::startsWith($file, '~')) {
            $file = "~/$file";
        }

        if (!File::exists($file)) {
            $file = null;
        }

        $source['id'] = sha1($file ?? '');
    }
}
