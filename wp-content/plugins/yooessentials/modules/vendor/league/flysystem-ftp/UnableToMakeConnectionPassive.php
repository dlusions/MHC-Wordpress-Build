<?php

declare (strict_types=1);
namespace ZOOlanders\YOOessentials\Vendor\League\Flysystem\Ftp;

use RuntimeException;
class UnableToMakeConnectionPassive extends RuntimeException implements FtpConnectionException
{
}
