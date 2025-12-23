<?php

declare (strict_types=1);
namespace ZOOlanders\YOOessentials\Vendor\League\Flysystem\PhpseclibV2;

use ZOOlanders\YOOessentials\Vendor\phpseclib\Net\SFTP;
class SimpleConnectivityChecker implements ConnectivityChecker
{
    public function isConnected(SFTP $connection) : bool
    {
        return $connection->isConnected();
    }
}
