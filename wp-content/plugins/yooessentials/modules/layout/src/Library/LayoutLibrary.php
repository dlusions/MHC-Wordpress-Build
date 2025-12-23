<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Layout\Library;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Data;
use ZOOlanders\YOOessentials\Storage\Storage;
use ZOOlanders\YOOessentials\Util\Arr;
use ZOOlanders\YOOessentials\Vendor\League\Flysystem\FileAttributes;
use ZOOlanders\YOOessentials\Vendor\League\Flysystem\Filesystem;
use ZOOlanders\YOOessentials\Vendor\League\Flysystem\StorageAttributes;
use ZOOlanders\YOOessentials\Vendor\Symfony\Contracts\Cache\CacheInterface;

class LayoutLibrary extends Data
{
    const INDEX_FILE = '_layouts.json';

    protected Storage $storage;

    protected ?CacheInterface $cache = null;

    protected ?bool $hasRemoteIndex = null;

    public function indexFilePath(): string
    {
        return $this->path() . '/' . self::INDEX_FILE;
    }

    public function id(): string
    {
        return $this->data['id'] ?? uniqid();
    }

    public function title(): string
    {
        return $this->data['title'] ?? $this->id();
    }

    public function writable(): bool
    {
        return $this->storage()->writable() && ($this->data['writable'] ?? true);
    }

    public function path(): string
    {
        return $this->data['path'] ?? '';
    }

    public function onStorage(Storage $storage): self
    {
        $this->storage = $storage;

        return $this;
    }

    public function storage(): ?Storage
    {
        return $this->storage;
    }

    public function filesystem(): ?Filesystem
    {
        if (!$this->storage()) {
            return null;
        }

        return $this->storage()->filesystem();
    }

    public function files(bool $refresh = false): array
    {
        if (!$this->filesystem()) {
            return [];
        }

        /*
         * FOR NOW, rely on the local cache, and that's it
         * TODO: reevaluate if the local index rebuild becomes too slow
         *

         // Is there a master file present? read it and be done with it
         if ($this->hasRemoteIndex()) {
             return $this->readRemoteIndex();
         }

        */

        $cacheKey = $this->localIndexCacheKey();

        if ($refresh) {
            $this->cache()->delete($cacheKey);
        }

        // Do we have a local cached master file?
        $files =
            $this->cache()->get($cacheKey, fn () => $this->writeLocalIndex()) ?? [];

        // Avoid caching empty lists
        if (count($files) <= 0) {
            $this->cache()->delete($cacheKey);
        }

        return $files;
    }

    public function upload(object $node): self
    {
        if (!$this->writable()) {
            throw new \Exception('Write Action Aborted: this Storage is set as Ready Only.');
        }

        $item = new IndexLibraryItem((array) $node);

        if (!$item->name()) {
            throw new \Exception('Node name is not set');
        }

        if (!$item->path()) {
            $item->withPath("{$this->path()}/{$item->id()}.json");
        }

        if (!isset($node->yooessentialsLayoutId) || !$node->yooessentialsLayoutId) {
            $node->yooessentialsLayoutId = $item->id();
        }

        if ($this->exists($item->id())) {
            $storedNode = $this->get($item->id());
            $item->withPath($storedNode->path());
        }

        // Write layout file
        $this->filesystem()->write($item->path(), self::jsonEncode($node));

        return $this->updateIndex($item);
    }

    public function exists(string $id): bool
    {
        return count(
            Arr::filter($this->files(), fn (IndexLibraryItem $item) => $item->id() === $id)
        ) > 0;
    }

    public function get(string $id): ?IndexLibraryItem
    {
        $nodes = Arr::filter($this->files(), fn (IndexLibraryItem $item) => $item->id() === $id);

        return array_shift($nodes);
    }

    public function read(string $id): ?object
    {
        $item = $this->get($id);
        if ($item === null) {
            return null;
        }

        return json_decode($this->filesystem()->read($item->path())) ?? null;
    }

    public function delete(string $id): self
    {
        if (!$this->writable()) {
            throw new \Exception('Delete Action Aborted: this Storage is set as Ready Only.');
        }

        $layouts = Arr::keyBy($this->files(), fn (IndexLibraryItem $item) => $item->id());

        /** @var IndexLibraryItem $layout */
        $layout = $layouts[$id] ?? null;

        if (!$layout) {
            throw new \Exception("Layout node with id '$id' does not exist");
        }

        $this->filesystem()->delete($layout->path());

        return $this->removeItemFromIndex($layout);
    }

    public function updateIndex(IndexLibraryItem $item): self
    {
        // refresh local index
        if (!$this->hasRemoteIndex()) {
            $this->refreshLocalIndex();
        }

        // We can't write the remote index at all, we're good to go
        if (!$this->writable()) {
            return $this;
        }

        $this->readLocalIndex();
        $this->hasRemoteIndex = null;

        return $this;

        /*
        * FOR NOW, rely on the local cache, and that's it
        * TODO: reevaluate if the local index rebuild becomes too slow
        *

        // Remote index is not present, let's try to generate it from the local one
        if (!$this->hasRemoteIndex()) {
            $index = $this->readLocalIndex();
            $this->filesystem()->write($this->indexFilePath(), self::jsonEncode(array_values($index)));
            $this->hasRemoteIndex = null;

            return $this;
        }

        // Update remote index
        $index = $this->updateIndexArray($this->readRemoteIndex(), $item);

        $this->filesystem()->write($this->indexFilePath(), self::jsonEncode(array_values($index)));
        $this->hasRemoteIndex = true;

        return $this;
        */
    }

    public function removeItemFromIndex(IndexLibraryItem $item): self
    {
        // refresh local index
        if (!$this->hasRemoteIndex()) {
            $this->refreshLocalIndex();
        }

        // We can't write the remote index at all, we're good to go
        if (!$this->writable()) {
            return $this;
        }

        $this->refreshLocalIndex();

        return $this;

        /*
       * FOR NOW, rely on the local cache, and that's it
       * TODO: reevaluate if the local index rebuild becomes too slow
       *

        // Remote index is not present, let's try to generate it from the local one
        if (!$this->hasRemoteIndex()) {
            $index = Arr::keyBy($this->files(), 'id');
            unset($index[$item->id()]);
            $this->filesystem()->write($this->indexFilePath(), self::jsonEncode(array_values($index)));
            $this->hasRemoteIndex = null;

            return $this;
        }

       // Update remote index
       $index = Arr::keyBy($this->files(), 'id');
       unset($index[$item->id()]);

       $this->filesystem()->write($this->indexFilePath(), self::jsonEncode(array_values($index)));
       $this->hasRemoteIndex = true;

       return $this;
        */
    }

    protected function updateIndexArray(array $index, IndexLibraryItem $item): array
    {
        $index = Arr::keyBy($index, 'id');
        $index[$item->id()] = $item;

        return $index;
    }

    public function refreshLocalIndex(): self
    {
        // Remote index present, do not use the local one
        if ($this->hasRemoteIndex()) {
            return $this;
        }

        $this->writeLocalIndex();

        return $this;
    }

    public function writeLocalIndex(): array
    {
        $this->cache()->delete($this->localIndexCacheKey());

        return $this->readLocalIndex();
    }

    public function readIndex(): array
    {
        if ($this->hasRemoteIndex()) {
            return $this->readRemoteIndex();
        }

        return $this->readLocalIndex();
    }

    public function readLocalIndex(): array
    {
        return $this->cache()->get($this->localIndexCacheKey(), fn () =>
            // Use the same method used on writing layouts on a storage.
            $this->filesystem()
            ->listContents($this->path())
            ->sortByPath()
            ->map(fn (StorageAttributes $item) => $this->map($item))
            ->filter(function (?IndexLibraryItem $item) {
                if ($item === null) {
                    return false;
                }

                return $item;
            })
            ->toArray());
    }

    protected function map(StorageAttributes $item): ?IndexLibraryItem
    {
        if (!$item->isFile()) {
            return null;
        }

        /** @var FileAttributes $item */
        if (pathinfo($item->path(), PATHINFO_EXTENSION) !== 'json') {
            return null;
        }

        $item = IndexLibraryItem::fromJsonFile($this->filesystem(), $item->path());

        return $item;
    }

    public function readRemoteIndex(): array
    {
        if ($this->hasRemoteIndex() === null) {
            return [];
        }

        return array_map(fn (array $item) => new IndexLibraryItem($item), json_decode($this->filesystem()->read($this->indexFilePath()), true) ?? []);
    }

    protected function hasRemoteIndex(): bool
    {
        if ($this->hasRemoteIndex === null) {
            return $this->hasRemoteIndex = $this->filesystem()->fileExists($this->indexFilePath());
        }

        return $this->hasRemoteIndex;
    }

    protected function cache(): CacheInterface
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        return $this->cache = app(CacheInterface::class);
    }

    protected function localIndexCacheKey(): string
    {
        $cacheKey = "yooessentials-layout-{$this->id()}";
        if ($this->storage()) {
            $cacheKey .= "-{$this->storage()->cacheKey()}";
        }

        return $cacheKey;
    }

    protected static function jsonEncode($value): string
    {
        return json_encode($value, JSON_PRETTY_PRINT);
    }
}
