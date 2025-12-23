<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Layout;

use ZOOlanders\YOOessentials\Storage\StorageService;

class LayoutManager
{
    protected StorageService $storages;

    /** @var Library\LayoutLibrary[] */
    protected array $libraries = [];

    /** @var string */
    public const LIBRARIES_CONFIG_KEY = 'layout.libraries';

    public function __construct(StorageService $storageService)
    {
        $this->storages = $storageService;
    }

    public function setLibraries(array $libraries): self
    {
        $libraries = array_filter(
            array_map(function (array $data) {
                $library = new Library\LayoutLibrary($data);

                $storageId = $data['storage'] ?? null;
                if (!$storageId) {
                    return $library;
                }

                $storage = $this->storages->storage($storageId);
                if (!$storage) {
                    return null;
                }

                return $library->onStorage($storage);
            }, $libraries)
        );

        $this->libraries = array_column($libraries, null, 'id');

        return $this;
    }

    public function library(string $id): ?Library\LayoutLibrary
    {
        return $this->libraries[$id] ?? null;
    }

    public function libraries(?string $storageId = null): array
    {
        if ($storageId === null) {
            return $this->libraries;
        }

        return array_filter($this->libraries, fn (Library\LayoutLibrary $library) => $library->storage() && $library->storage()->id() === $storageId);
    }
}
