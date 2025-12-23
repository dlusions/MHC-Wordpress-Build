<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration;

use ZOOlanders\YOOessentials\Util\Arr;
use function YOOtheme\app;

class MigrationService
{
    protected string $version;

    protected array $updates = [];

    /** @var MigrationInterface[] */
    protected array $migrations = [];

    /** @var class-string<MigrationInterface>[] */
    protected array $migrationClasses = [];

    public function __construct(string $version)
    {
        $this->version = $version;
    }

    public function addMigration(string $migration): self
    {
        if (!class_exists($migration)) {
            throw new \InvalidArgumentException(sprintf('Migration class %s does not exist', $migration));
        }

        if (!is_subclass_of($migration, MigrationInterface::class)) {
            throw new \InvalidArgumentException(sprintf('Migration class %s must be an instance of %s', $migration, MigrationInterface::class));
        }

        $this->migrationClasses[] = $migration;

        return $this;
    }

    public function migrateConfig(array $config): array
    {
        $version = $config['version'] ?? '0.0.1';

        // check config version
        if (version_compare($version, $this->version, '>=')) {
            return $config;
        }

        $sha = sha1(json_encode($config));

        // apply migration callbacks
        foreach ($this->resolveMigrations($version) as $migration) {
            $config = $migration->migrateConfig($config);
        }

        $config['version'] = $this->version;

        // give a hint to customizer there was an update
        $config['updated'] = $sha !== sha1(json_encode($config));

        return $config;
    }

    public function migrateNode(object $node, array &$params): void
    {
        if (isset($node->transient)) {
            return;
        }

        if (isset($node->yooessentialsVersion)) {
            $params['yooessentialsVersion'] = $node->yooessentialsVersion;
        } elseif (empty($params['yooessentialsVersion'])) {
            $params['yooessentialsVersion'] = '1.0.0';
        }

        if (empty($params['parent'])) {
            $node->yooessentialsVersion = $this->version;
        } else {
            unset($node->yooessentialsVersion);
        }

        $version = $params['yooessentialsVersion'] ?? '';

        // check node version
        if (version_compare($version, $this->version, '>=')) {
            return;
        }

        // take only correct migrations
        /** @var MigrationInterface[] $migrations */
        $migrations = Arr::filter($this->resolveMigrations($version), function (MigrationInterface $migration) use ($node) {
            // Applies to all nodes
            if ($migration->type() === null) {
                return true;
            }

            // Specific node
            return $migration->type() === $node->type;
        });

        // apply migration callbacks
        foreach ($migrations as $migration) {
            $migration->migrateNode($node, $params);
        }
    }

    /**
     * Resolves updates from migrations
     * @return MigrationInterface[]
     */
    protected function resolveMigrations(string $version): array
    {
        if (isset($this->updates['#migrations'][$version])) {
            return $this->updates['#migrations'][$version];
        }

        $resolved = [];

        foreach ($this->migrations() as $migration) {
            if (
                version_compare($migration->version(), $version, '>=')
                && version_compare($migration->version(), $this->version, '<=')
            ) {
                $resolved[$migration->version()][] = $migration;
            }
        }

        uksort($resolved, 'version_compare');

        return $this->updates['#migrations'][$version] = $resolved ? array_merge(...array_values($resolved)) : [];
    }

    /**
     * @return MigrationInterface[]
     */
    public function migrations(): array
    {
        if (!empty($this->migrations)) {
            return $this->migrations;
        }

        return $this->migrations = Arr::map($this->migrationClasses, fn (string $class) => app($class));
    }
}
