<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration;

abstract class AbstractMigration implements MigrationInterface
{
    public const VERSION = '';

    public function version(): string
    {
        return static::VERSION;
    }

    public function migrateConfig(array $config): array
    {
        return $config;
    }

    public function migrateNode(object $node, array $params): void
    {

    }

    public function type(): ?string
    {
        return null;
    }
}
