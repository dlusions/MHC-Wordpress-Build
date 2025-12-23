<?php

declare (strict_types=1);
namespace ZOOlanders\YOOessentials\Vendor\League\Flysystem;

interface PathNormalizer
{
    public function normalizePath(string $path) : string;
}
