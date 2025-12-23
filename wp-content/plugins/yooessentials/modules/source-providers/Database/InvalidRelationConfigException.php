<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Database;

use RuntimeException;

class InvalidRelationConfigException extends RuntimeException
{
    protected array $config;

    public function __construct(string $message, array $config = [])
    {
        $message = "Invalid Relation configuration: $message.";

        $this->config = $config;

        parent::__construct($message, 400);
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}
