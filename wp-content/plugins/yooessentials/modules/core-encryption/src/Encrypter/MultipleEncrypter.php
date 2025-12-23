<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Encryption\Encrypter;

use ZOOlanders\YOOessentials\Encryption\Encrypter;

class MultipleEncrypter implements Encrypter
{
    /**
     * @var Encrypter[]
     */
    protected array $encrypters;

    /**
     * @param Encrypter[] $encrypters
     */
    public function __construct(array $encrypters)
    {
        $this->encrypters = $encrypters;
    }

    public function encrypt($data, $serialize = true): string
    {
        $lastException = null;
        foreach ($this->encrypters as $encrypter) {
            try {
                $value = $encrypter->encrypt($data, $serialize);
                if (!$value) {
                    throw new \Exception('Could not encrypt data with encrypter ' . get_class($encrypter));
                }

                return $value;
            } catch (\Throwable $e) {
                $lastException = $e;
            }
        }

        throw $lastException ?? new \RuntimeException('No encrypter available');
    }

    public function decrypt($data, $serialize = true)
    {
        $lastException = null;
        foreach ($this->encrypters as $encrypter) {
            try {
                $value = $encrypter->decrypt($data, $serialize);

                if (!$value) {
                    continue;
                }

                return $value;
            } catch (\Throwable $e) {
                $lastException = $e;
            }
        }

        if ($lastException !== null) {
            return $lastException;
        }

        return '';
    }
}
