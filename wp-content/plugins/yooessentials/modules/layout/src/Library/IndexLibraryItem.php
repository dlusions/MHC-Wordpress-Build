<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Layout\Library;

use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Data;
use ZOOlanders\YOOessentials\Vendor\League\Flysystem\Filesystem;

class IndexLibraryItem extends Data
{
    public function __construct(array $data = [])
    {
        $id = $data['yooessentialsLayoutId'] ?? ($data['id'] ?? uniqid());

        $defaults = [
            'id' => $id,
            'name' => '',
            'path' => $id . '.json',
            'created' => (new \DateTime())->format(\DateTime::ATOM),
            'modified' => (new \DateTime())->format(\DateTime::ATOM),
            'version' => '1.0.0',
            'yooessentialsVersion' => '1.0.0',
            'type' => '',
            'tags' => [],
            'platforms' => [],
        ];

        $data['modified'] ??= $defaults['modified'];
        $data['created'] ??= $data['modified'] ?? $defaults['created'];

        $data = array_merge($defaults, Arr::pick($data, array_keys($defaults)));

        parent::__construct($data);
    }

    public static function fromJsonFile(Filesystem $filesystem, string $path): self
    {
        $data = json_decode($filesystem->read($path), true);

        $data['modified'] ??= \DateTime::createFromFormat('U', $filesystem->lastModified($path))->format(DATE_ATOM);
        $data['created'] ??= $data['modified'];

        return (new self($data))->withPath($path);
    }

    public function withIcon(string $icon): self
    {
        $this->data['icon'] = $icon;

        return $this;
    }

    public function withImage(string $image): self
    {
        $this->data['image'] = $image;

        return $this;
    }

    public function withPath(string $path): self
    {
        $this->data['path'] = $path;

        return $this;
    }

    public function id(): string
    {
        return $this->id ?? '';
    }

    public function name(): ?string
    {
        return $this->name ?? null;
    }

    public function path(): ?string
    {
        return $this->path ?? null;
    }

    public function image(): ?string
    {
        return $this->image ?? null;
    }

    public function icon(): ?string
    {
        return $this->icon ?? null;
    }

    public function type(): string
    {
        return $this->type ?? '';
    }

    public function version(): string
    {
        return $this->version ?? '';
    }

    public function yooessentialsVersion(): string
    {
        return $this->yooessentialsVersion ?? '';
    }

    /* TODO */
    public function modified(): \DateTimeInterface
    {
        if ($date = \DateTime::createFromFormat($this->modified, \DateTime::ATOM)) {
            return $date;
        }

        return new \DateTime();
    }

    /* TODO */
    public function created(): \DateTimeInterface
    {
        if ($date = \DateTime::createFromFormat($this->created, \DateTime::ATOM)) {
            return $date;
        }

        return new \DateTime();
    }

    public function platforms(): array
    {
        return $this->platforms ?? [];
    }

    public function tags(): array
    {
        return $this->tags ?? [];
    }
}
