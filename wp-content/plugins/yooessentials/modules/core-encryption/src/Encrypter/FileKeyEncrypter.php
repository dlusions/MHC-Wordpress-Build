<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Encryption\Encrypter;

use YOOtheme\Config;
use YOOtheme\Path;
use YOOtheme\Str;

class FileKeyEncrypter extends GenericEncrypter
{
    protected string $keyFilePath;

    public function __construct(Config $config)
    {
        $this->keyFilePath = $config->get('yooessentials.encryption.key', '~yooessentials/secret.php');

        if (!$this->keyFileExists()) {
            $this->createKeyFile();
        }

        parent::__construct($this->key());
    }

    private function keyFileExists(): bool
    {
        return file_exists($this->keyFile());
    }

    private function createKeyFile(): void
    {
        $key = Str::random(64);

        file_put_contents($this->keyFile(), '<?php return "' . $key .'";');
    }

    private function keyFile(): ?string
    {
        return Path::resolve($this->keyFilePath);
    }

    private function key(): string
    {
        return include_once $this->keyFile();
    }
}
