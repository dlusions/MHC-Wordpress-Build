<?php

declare (strict_types=1);
namespace ZOOlanders\YOOessentials\Vendor\League\MimeTypeDetection;

interface ExtensionToMimeTypeMap
{
    public function lookupMimeType(string $extension) : ?string;
}
