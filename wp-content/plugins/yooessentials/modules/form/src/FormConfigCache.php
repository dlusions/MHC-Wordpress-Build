<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form;

use YOOtheme\File;
use YOOtheme\Path;

class FormConfigCache
{
    protected string $prefix;

    protected string $cache;

    /**
     * Constructor.
     */
    public function __construct(string $prefix)
    {
        $this->prefix = $prefix;
        $this->cache = Path::resolve('~theme/cache/yooessentials');

        File::makeDir($this->cache, 0777, true);
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Return the cache path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->cache;
    }

    /**
     * Returns the cached asset path
     */
    public function get(string $name): ?string
    {
        $file = $this->resolve($name);

        if (File::exists($file)) {
            return File::getContents($file);
        }

        return null;
    }

    /**
     * Saves the asset in the resolved cache ubication
     *
     * @param mixed $data
     */
    public function set(string $name, $data): ?int
    {
        return File::putContents($this->resolve($name), $data);
    }

    /**
     * Checks whether asset is cached
     */
    public function exists(string $name): bool
    {
        return File::exists($this->resolve($name));
    }

    /**
     * Gets the inode change time of asset
     */
    public function getCTime(string $name): ?int
    {
        return File::getCTime($this->resolve($name));
    }

    /**
     * Resolves path to cache asset
     */
    public function resolve(string $name): string
    {
        return Path::resolve($this->cache, "$this->prefix-$name");
    }

    /**
     * Clear che cache for a given key
     */
    public function clear(string $name): bool
    {
        if (!$this->exists($name)) {
            return true;
        }

        return File::delete($this->resolve($name));
    }
}
