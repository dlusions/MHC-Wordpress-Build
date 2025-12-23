<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Icon;

use YOOtheme\Config;
use YOOtheme\File;
use YOOtheme\Str;
use ZOOlanders\YOOessentials\HttpClientInterface;
use ZOOlanders\YOOessentials\Unzipper;

class IconApi
{
    public Config $config;

    public HttpClientInterface $client;

    public Unzipper $unzipper;

    public IconLoader $loader;

    public function __construct(Config $config, HttpClientInterface $client, IconLoader $loader, Unzipper $unzipper)
    {
        $this->config = $config;
        $this->loader = $loader;
        $this->client = $client;
        $this->unzipper = $unzipper;
    }

    /**
     * Load collection package.
     */
    public function loadCollection(string $name): void
    {
        $location = $this->loader->getLocation();
        $collections = $this->loader->getCollections();

        $manifest = $collections[$name] ?? null;

        if (!$manifest) {
            throw new \Exception('Unknown collection: ' . $name);
        }

        if (File::exists("{$location}/$name") && File::exists("{$location}/$name.json")) {
            return;
        }

        if (!$manifest->package) {
            throw new \Exception('Missing package url in collection manifest.');
        }

        $tmp = $this->config->get('app.tempDir') . '/' . uniqid();
        $packed = "$tmp/package.zip";
        $unpacked = "$tmp/package";

        try {
            $data = $this->downloadCollection($manifest);
        } catch (\Throwable $e) {
            throw new \Exception("Failed to download '$name' collection.", $e->getCode(), $e);
        }

        if (!File::makeDir($unpacked, 0777, true)) {
            throw new \Exception('Failed to create temp folder.');
        }

        File::putContents($packed, $data);

        $this->unzipper->unzip($packed, $unpacked);

        $manifest = File::glob("$unpacked/*.json")[0] ?? false;

        if (!$manifest) {
            throw new \Exception('Missing manifest file in downloaded package.');
        }

        $manifest = json_decode(File::getContents($manifest), true);
        $name = $manifest['name'] ?? false;

        if (!$name) {
            throw new \Exception('Missing name in downloaded package manifest.');
        }

        $this->unzipper->unzip($packed, $location);

        // update manifest
        $manifest['installed'] = $manifest['version'];
        File::putContents("{$location}/$name.json", json_encode($manifest, JSON_PRETTY_PRINT));
    }

    public function downloadCollection($manifest): string
    {
        $response = $this->client->get($manifest->package);

        return (string) $response->getBody();
    }

    /**
     * Remove installed collection.
     */
    public function removeCollection(string $name): void
    {
        $location = $this->loader->getLocation();
        $manifest = $this->loader->getCollections()[$name];

        if (!$manifest) {
            throw new \Exception('Unknown collection: ' . $name);
        }

        // skip if already removed
        if (!(File::exists("{$location}/$name") && File::exists("{$location}/$name.json"))) {
            return;
        }

        File::delete("{$location}/$name.json");
        File::deleteDir("{$location}/$name");
    }

    /**
     * Fetch icons for UI related task.
     */
    public function fetchIcons($offset, $length, ?string $search = null, ?string $collection = null, ?string $group = null): array
    {
        $collections = $this->loader->getCollections();

        // filter out non installed
        $collections = array_filter($collections, fn ($col) => $col->installed);

        // exclude myicons unless explicitly fetching it
        if ($collection !== 'myicons') {
            $collections = array_filter($collections, fn ($col) => $col->name !== 'myicons');
        }

        // apply ui filter
        if ($collection) {
            $collections = array_filter($collections, fn ($col) => $col->name === $collection);
        }

        $data = [];

        foreach ($collections as $col) {
            $groups = $col->groups;

            if ($group) {
                $groups = array_filter($groups, fn ($g) => $g === $group);
            }

            foreach ($groups as $gr) {
                $pattern = $col->location . '/';
                $pattern .= $gr ? "$gr/" : '';
                $pattern .= $search ? '*' . Str::lower($search) . '*' : '*';
                $pattern .= '.svg';

                $icons = File::glob($pattern);

                foreach ($icons as $icon) {
                    $name = basename($icon, '.svg');
                    $content = File::getContents($icon);

                    // simple svg cleanup
                    $content = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $content);
                    $content = preg_replace('/\s{2,}/', ' ', $content);
                    $content = preg_replace('/\n/', '', $content);

                    if ($gr) {
                        $key = sprintf('%s-%s--%s', $col->name, $gr, $name);
                    } else {
                        $key = sprintf('%s--%s', $col->name, $name);
                    }

                    $data[$key] = $content;
                }
            }
        }

        $total = count($data);
        $data = array_splice($data, $offset, $length);

        return ['data' => $data, 'total' => $total, 'offset' => $offset, 'length' => $length, 'search' => $search, 'collection' => $collection, 'group' => $group];
    }
}
