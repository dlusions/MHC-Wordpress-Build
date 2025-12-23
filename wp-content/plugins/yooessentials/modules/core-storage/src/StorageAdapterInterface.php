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

interface StorageAdapterInterface
{
    public function metadata(): object;

    public function name(): string;

    public function filesystem(array $config = []): Filesystem;

    public function adapter(array $config = []): FilesystemAdapter;

    public function validateConfig(array $config): void;
}
