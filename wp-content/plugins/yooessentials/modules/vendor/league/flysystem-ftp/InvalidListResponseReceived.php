<?php

declare (strict_types=1);
namespace ZOOlanders\YOOessentials\Vendor\League\Flysystem\Ftp;

use ZOOlanders\YOOessentials\Vendor\League\Flysystem\FilesystemException;
use RuntimeException;
class InvalidListResponseReceived extends RuntimeException implements FilesystemException
{
}
