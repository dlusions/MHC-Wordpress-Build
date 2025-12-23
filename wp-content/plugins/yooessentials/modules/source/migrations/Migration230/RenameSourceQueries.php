<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration230;

use function YOOtheme\app;
use YOOtheme\Str;
use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Config\Config;
use ZOOlanders\YOOessentials\Migration\Migration230Helper;
use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Util;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class RenameSourceQueries extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.3.0';

    public function migrateConfig(array $config): array
    {
        $sources = Arr::get($config, 'source.sources', []);

        Util\Config::iterateGlobalQueries($config, function (&$query) use ($sources) {
            self::migrateQuery($query, $sources);
        });

        return $config;
    }

    public function migrateNode(object $node, array $params): void
    {
        $sources = app(Config::class)->get('source.sources', []);

        Migration230Helper::iterateNodeSources($node, function ($source) use ($sources) {
            if (isset($source->query)) {
                self::migrateQuery($source->query, $sources);
            }
        });
    }

    protected static function migrateQuery(object $query, array $sources)
    {
        if (!isset($query->name) || !Str::endsWith($query->name, '_query')) {
            return;
        }

        // airtable{id}_{query}_query -> airtable{id}.{query}
        if (preg_match('/airtable(\D+)_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[2]);
            $name = Str::camelCase($matches[1]);
            $query->name = "airtable{$id}.{$name}";
        }

        // cloudflareStream{id}_{query}_query -> cloudflareStream{id}.{query}
        if (preg_match('/cloudflareStream(\D+)_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[2]);
            $name = Str::camelCase($matches[1]);
            $query->name = "cloudflareStream{$id}.{$name}";
        }

        // fileCSV_{$id}_quer -> csv{id}.records
        if (preg_match('/fileCSV_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[1]);
            $query->name = "csv{$id}.records";
        }

        // database{id}_{query}_query -> database{id}.{query}
        if (preg_match('/database(\D+)_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[2]);
            $name = Str::camelCase($matches[1]);
            $query->name = "database{$id}.{$name}";
        }

        // facebook_{id}_page_posts_query -> facebook{id}.pagePosts
        if (preg_match('/facebook_(\w{6})_(\D+)_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[1]);
            $name = Str::camelCase($matches[2]);
            $query->name = "facebook{$id}.{$name}";
        }

        // googleMyBusiness{id}_{query}_query -> googleBusinessProfile{id}.{query}
        if (preg_match('/googleMyBusiness(\D+)_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[2]);
            $name = Str::camelCase($matches[1]);
            $query->name = "googleBusinessProfile{$id}.{$name}";
        }

        if (preg_match('/googleBusinessProfile(\w{6}).medias/i', $query->name, $matches)) {
            $id = Str::upper($matches[1]);
            $query->name = "googleBusinessProfile{$id}.media";
        }

        // googleCalendar{id}_{query}_query -> googleCalendar{id}.{query}
        if (preg_match('/googleCalendar(\D+)_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[2]);
            $name = Str::camelCase($matches[1]);
            $query->name = "googleCalendar{$id}.{$name}";
        }

        // googlePhotos{id}_{query}_query -> googlePhotos{id}.{query}
        if (preg_match('/googlePhotos(\D+)_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[2]);
            $name = Str::camelCase($matches[1]);
            $query->name = "googlePhotos{$id}.{$name}";
        }

        // googleSheets{id}_{query}_query -> googleSheets{id}.{query}
        if (preg_match('/googleSheet_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[1]);
            $query->name = "googleSheets{$id}.records";
        }

        // rssfeed_{id}_query -> rss{id}.feed
        if (preg_match('/rssfeed_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[1]);
            $query->name = "rss{$id}.feed";
        }

        // rssfeed_{id}_items_query -> rss{id}.items
        if (preg_match('/rssfeed_(\w{6})_items_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[1]);
            $query->name = "rss{$id}.items";
        }

        // instagram_{id}_query -> instagramX?{id}.media
        if (preg_match('/instagram_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[1]);
            $query->name = "instagram{$id}.media";
        }

        // instagram_single_media_{id}_query -> instagramX?{id}.singleMedia
        if (preg_match('/instagram_single_media_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[1]);
            $query->name = "instagram{$id}.singleMedia";
        }

        // instagramUser_{id}_query -> instagram{id}.user
        if (preg_match('/instagramUser_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[1]);
            $query->name = "instagram{$id}.user";
        }

        // instagramHashtaggedMedia_{id}_query -> instagram{id}.hashtaggedMedia
        if (preg_match('/instagramHashtaggedMedia_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[1]);
            $query->name = "instagram{$id}.hashtaggedMedia";
        }

        // tiktok{id}_{query}_query -> tiktok{id}.{query}
        if (preg_match('/tiktok(\D+)_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[2]);
            $name = Str::camelCase($matches[1]);
            $query->name = "tiktok{$id}.{$name}";
        }

        // twitter_{id}_page_posts_query -> twitter{id}.pagePosts
        if (preg_match('/twitter_(\w{6})_(\D+)_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[1]);
            $name = Str::camelCase($matches[2]);
            $query->name = "twitter{$id}.{$name}";
        }

        // vimeo{id}_{query}_query -> vimeo{id}.{query}
        if (preg_match('/vimeo(\D+)_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[2]);
            $name = Str::camelCase($matches[1]);
            $query->name = "vimeo{$id}.{$name}";
        }

        // youtubeChannel_{id}_query -> youtubeChannel{id}.channel
        if (preg_match('/youtubeChannel_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[1]);
            $query->name = "youtubeChannel{$id}.channel";
        }

        // youtubeChannelXxx_{id}_query -> youtubeChannel{id}.xxx
        if (preg_match('/youtubeChannel(\D+)_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[2]);
            $name = Str::camelCase($matches[1]);
            $query->name = "youtubeChannel{$id}.{$name}";
        }

        // youtubePlaylistXxx{id}_query -> youtubePlaylist{id}.xxx
        if (preg_match('/youtubePlaylist(\D+)_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[2]);
            $name = Str::camelCase($matches[1]);
            $query->name = "youtubePlaylist{$id}.{$name}";
        }

        // youtubeXxx{id}_query -> youtube{id}.xxx
        if (preg_match('/youtube(\D+)_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[2]);
            $name = Str::camelCase($matches[1]);
            $query->name = "youtube{$id}.{$name}";
        }

        // XML_{id}_query -> xmlX?{id}.root
        if (preg_match('/XML_(\w{6})_query/i', $query->name, $matches)) {
            $id = Str::upper($matches[1]);
            $sourceConfig = Arr::find($sources, fn ($c) => $c['id'] === $id);

            if ($sourceConfig && $sourceConfig['provider'] === 'xml-file') {
                $query->name = "xmlFile{$id}.root";
            } else {
                $query->name = "xmlUrl{$id}.root";
            }
        }
    }
}
