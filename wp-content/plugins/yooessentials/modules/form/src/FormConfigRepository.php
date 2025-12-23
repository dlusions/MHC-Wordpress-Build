<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form;

use ZOOlanders\YOOessentials\Util;
use ZOOlanders\YOOessentials\Vendor\Symfony\Contracts\Cache\CacheInterface;

class FormConfigRepository
{
    protected FormConfigCache $oldCache;

    protected CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
        $this->oldCache = new FormConfigCache('form');
    }

    /**
     * Save config for a form
     * @param  string  $id
     * @param  array  $config
     */
    public function saveConfig(string $id, array $config): void
    {
        $key = $this->cacheKey($id);
        $value = json_decode(json_encode($config), true);

        if (!is_array($value)) {
            return;
        }

        // Delete old version and set it fresh
        $this->cache->delete($key);
        $this->cache->get($key, fn () => $value);
    }

    /**
     * Loads the config from the old .php files in cache
     *
     * @param string $formId
     * @return array
     */
    private function loadOldConfig(string $formId): array
    {
        $key = sprintf('%s.php', $formId);
        $cached = $this->oldCache->resolve($key);

        if (!is_file($cached)) {
            return [];
        }

        $config = include $cached;

        // Something weird here happened, let's clear the cache and return an empty array
        if (!is_array($config)) {
            $this->oldCache->clear($key);

            return [];
        }

        return $config;
    }

    /**
     * Load the Config for a form
     * @param  string  $formId
     * @return array
     */
    public function loadConfig(string $formId): array
    {
        $key = $this->cacheKey($formId);

        $config = $this->cache->get($key, function () use ($formId) {
            // Read the old config files
            $oldConfig = $this->loadOldConfig($formId);

            // TODO: Clear the old config files
            // $this->oldCache->clear(sprintf('%s.php', $formId));

            return $oldConfig;
        });

        if (!is_array($config)) {
            $this->cache->delete($key);

            return [];
        }

        if (empty($config)) {
            $this->cache->delete($key);

            return $this->loadOldConfig($formId);
        }

        return $config;
    }

    private function cacheKey(string $formId): string
    {
        return 'form.config.' . $formId;
    }

    /**
     * Save config for a form in the old cache
     * @param  string  $id
     * @param  array  $config
     */
    public function saveOldConfig(string $id, array $config): void
    {
        $key = sprintf('%s.php', $id);
        $value = json_decode(json_encode($config), true);

        if (!is_array($value)) {
            return;
        }

        $value = Util\Misc::compileValue($value);

        $this->oldCache->set($key, "<?php\n\nreturn {$value};\n");
    }
}
