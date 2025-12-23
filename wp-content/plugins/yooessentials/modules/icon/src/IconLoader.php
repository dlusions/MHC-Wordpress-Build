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
use YOOtheme\Path;
use YOOtheme\Str;
use ZOOlanders\YOOessentials\Vendor\Symfony\Contracts\Cache\CacheInterface;

class IconLoader
{
    /**
     * Queue for icons to be loaded
     */
    protected array $queue = [];

    /**
     * List of rendered icons
     */
    protected array $rendered = [];

    protected string $location;

    protected array $locations = [];

    protected array $collections = [];

    protected CacheInterface $cache;

    protected Config $config;

    public function __construct(Config $config, CacheInterface $cache)
    {
        $this->cache = $cache;
        $this->config = $config;
    }

    public function yetToRender(array $icons = []): array
    {
        $icons = array_merge($this->queued(), $icons);

        return array_diff_key($icons, $this->rendered());
    }

    public function queued(): array
    {
        return $this->queue;
    }

    public function rendered(): array
    {
        return $this->rendered;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation($location): self
    {
        $this->location = Path::resolve($location);

        $this->addLocation($location);

        return $this;
    }

    public function getLocations(): array
    {
        return $this->locations;
    }

    public function addLocation(string $location): self
    {
        $location = Path::resolve($location);

        if (!File::isDir($location)) {
            File::makeDir($location, 0777, true);
        }

        $this->locations[] = $location;

        return $this;
    }

    public function getCollections(): array
    {
        foreach ($this->locations as $location) {
            foreach (File::glob("$location/*", GLOB_ONLYDIR) as $path) {
                $name = basename($path);
                $collection = (array) ($this->collections[$name]->data ?? []);

                // if collection not registered do it on the fly
                if (empty($collection)) {
                    $collection = [
                        'name' => $name,
                        'title' => Str::titleCase($name),
                    ];
                }

                $collection['installed'] = true;
                $collection['location'] = "$location/$name";

                $manifest = Path::resolve($path, '..', "{$name}.json");
                if (File::exists($manifest)) {
                    $manifest = $this->config->loadFile($manifest);

                    $collection = array_merge($collection, $manifest);
                    $collection['installed'] = $manifest['version'] ?? true;
                }

                // set groups
                $collection['groups'] = $this->getCollectionGroups($path);

                $this->addCollection($collection);
            }
        }

        $myIconsLocation = $this->getMyIconsLocation();

        if ($myIconsLocation) {
            $this->addCollection([
                'name' => 'myicons',
                'title' => 'My Icons',
                'groups' => $this->getCollectionGroups($myIconsLocation),
                'location' => $myIconsLocation,
                'installed' => true,
            ]);
        }

        return $this->collections;
    }

    protected function addCollection(array $manifest): self
    {
        if (isset($manifest['name'])) {
            $this->collections[$manifest['name']] = new Collection($manifest);
        }

        return $this;
    }

    public function registerCollections(string $path): self
    {
        if (!$this->config->get('app.isAdmin')) {
            return $this;
        }

        $collections = array_map([$this->config, 'loadFile'], File::glob($path) ?? []);

        foreach ($collections as $collection) {
            $this->addCollection($collection);
        }

        return $this;
    }

    protected function getCollectionGroups($dir): array
    {
        $icons = File::glob("$dir/*.svg");
        $folders = File::glob("$dir/*", GLOB_ONLYDIR);

        $groups = array_values(
            array_map(fn ($v) => basename($v), $folders)
        );

        if (count($icons)) {
            array_unshift($groups, '');
        }

        return $groups;
    }

    public function loadIcon(string $icon): ?string
    {
        $isProvided = Str::contains($icon, '--');

        if (!$isProvided) {
            return null;
        }

        $cacheKey = sha1("icon-{$icon}.svg");
        $content = $this->cache->get($cacheKey, function () use ($icon) {
            if ($file = $this->findIcon($icon)) {
                $content = File::getContents($file);

                // simple svg cleanup
                $content = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $content);
                $content = preg_replace('/\s{2,}/', ' ', $content);
                $content = preg_replace('/\n/', '', $content);

                return $content;
            }

            return null;
        });

        // null cache if invalid icon
        if (!$content) {
            $this->cache->delete($cacheKey);

            return null;
        }

        $this->queue[$icon] = $content;

        return $content;
    }

    public function addRenderedIcons(array $icons): ?self
    {
        $this->rendered = array_merge($this->rendered, $icons);

        return $this;
    }

    /**
     * Finds the icon and returns it first location path
     */
    protected function findIcon(string $icon): ?string
    {
        list('collection' => $collection, 'group' => $group, 'name' => $name) = $this->parseIconKey($icon);

        $basepath = Path::join($group, $name);

        $myIconsLocation = $this->getMyIconsLocation();

        if ($collection === 'myicons' && $myIconsLocation) {
            if ($file = File::find("{$myIconsLocation}/$basepath.svg")) {
                return $file;
            }
        }

        foreach ($this->locations as $location) {
            if ($file = File::find("$location/$collection/$basepath.svg")) {
                return $file;
            }
        }

        return null;
    }

    /**
     * Parse icon info from it value
     */
    protected function parseIconKey(string $key): array
    {
        preg_match('/^([^-]*)-?(.*)--(.*)$/', $key, $matches);
        list($match, $collection, $group, $name) = $matches;

        return ['collection' => $collection, 'group' => $group, 'name' => $name];
    }

    /**
     * Retrieve icons from a node
     */
    public function retrieveIcons(object $node, object $type): array
    {
        $fields = array_merge(
            (array) ($type->data['fields'] ?? []),
            (array) ($type->data['fieldset']['default']['fields'] ?? [])
        );

        $icons = array_merge(
            $this->_retrieveIconsFields($node, $fields),
            $this->_retrieveIconsHtml($node, $fields)
        );

        // recursively iterate over fields
        foreach ($fields as $field) {
            $icons = array_merge($icons, $this->retrieveIcons($node, (object) ['data' => $field]));
        }

        return array_values(array_unique($icons));
    }

    /**
     * Retrieve icons from known icon fields
     */
    public function _retrieveIconsFields(object $node, array $fields): array
    {
        $icons = [];

        // get all fields with name including 'icon'
        $fieldsKeys = array_filter(array_keys($fields), fn ($key) => strpos($key ?? '', 'icon') !== false);

        foreach ($fieldsKeys as $key) {
            $value = $node->props[$key] ?? false;

            if (is_string($value)) {
                // icon could be set raw or with attributes (unusual but still expected),
                // we always explode and assume the icon name is set first
                list($iconName) = explode(';', $value);

                $icons[] = trim($iconName);
            }
        }

        return $icons;
    }

    /**
     * Retrieve icons from HTML declarations, eg <span uk-icon/>
     */
    protected function _retrieveIconsHtml(object $node, array $fields): array
    {
        $icons = [];
        $content = [];

        // iterate fields content
        foreach (array_keys($fields) as $key) {
            if (($value = $node->props[$key] ?? false) and is_string($value)) {
                $content[] = $value;
            }
        }

        // iterate source content filters (those can store html)
        foreach (array_merge(
            (array) ($node->source->props ?? []),
            (array) ($node->source_extended->props ?? [])
        ) as $prop) {
            foreach ($prop->filters ?? [] as $value) {
                if (is_string($value)) {
                    $content[] = $value;
                }
            }
        }

        // iterate over composed sources
        foreach ((array) ($node->source_extended->props ?? []) as $prop) {
            if ($prop->composed ?? false) {
                $content[] = $prop->composed->value ?? '';
            }
        }

        // match all icons set as html
        foreach ($content as $value) {
            if (strpos($value, 'uk-icon') !== false) {
                if (preg_match_all('/<[^\/]*?uk-icon.*?>/', $value, $allMatches)) {
                    foreach ($allMatches[0] as $match) {
                        if (preg_match('/[\w-]*--[\w-]*/', $match, $matches)) {
                            $icons[] = $matches[0];
                        }
                    }
                }
            }
        }

        return $icons;
    }

    private function getMyIconsLocation(): ?string
    {
        $myiconsDir = $this->config->get('theme.childDir') . '/myicons';

        if ($this->config->get('theme.childDir') && File::exists($myiconsDir)) {
            return $myiconsDir;
        }

        return null;
    }
}
