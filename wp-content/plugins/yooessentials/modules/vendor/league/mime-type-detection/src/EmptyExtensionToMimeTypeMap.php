<?php

declare (strict_types=1);
namespace ZOOlanders\YOOessentials\Vendor\League\MimeTypeDetection;

class EmptyExtensionToMimeTypeMap implements ExtensionToMimeTypeMap
{
    public function lookupMimeType(string $extension) : ?string
    {
        return null;
    }
}
