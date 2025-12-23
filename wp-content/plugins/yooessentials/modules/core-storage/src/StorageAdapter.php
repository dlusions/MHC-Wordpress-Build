<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Storage;

use ZOOlanders\YOOessentials\Vendor\League\Flysystem\Filesystem;
use ZOOlanders\YOOessentials\Vendor\League\Flysystem\FilesystemAdapter;

abstract class StorageAdapter implements StorageAdapterInterface
{
    protected ?object $metadata = null;

    public function __construct(array $metadata)
    {
        $this->metadata = (object) $metadata;
    }

    public function metadata(): object
    {
        return $this->metadata;
    }

    public function name(): string
    {
        return $this->metadata()->title ?? ($this->metadata()->name ?? uniqid());
    }

    public function filesystem(array $config = []): Filesystem
    {
        return new Filesystem($this->adapter($config));
    }

    abstract public function adapter(array $config = []): FilesystemAdapter;

    abstract public function validateConfig(array $config): void;

    public function storage(array $config): Storage
    {
        return (new Storage($config))->withAdapter($this);
    }
}
