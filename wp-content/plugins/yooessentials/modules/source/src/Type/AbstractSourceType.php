<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Type;

use function YOOtheme\app;
use YOOtheme\Str;
use YOOtheme\Path;
use YOOtheme\Config;

abstract class AbstractSourceType implements SourceInterface
{
    protected object $metadata;

    protected array $config = [];

    protected string $configFile = 'config.json';

    protected string $id;

    protected MainQueryType $queryType;

    public function __construct(array $config = [])
    {
        $this->queryType = new MainQueryType($this);
        $this->metadata = (object) app(Config::class)->loadFile($this->metadataFile());
        $this->bind($config);
    }

    public function config(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->config;
        }

        return $this->config[$key] ?? $default;
    }

    public function metadata(): object
    {
        return $this->metadata;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->config('name') ?: $this->metadata->title ?? '';
    }

    public function description(): string
    {
        return $this->config('description') ?: $this->metadata->description ?? '';
    }

    public function queryName(): string
    {
        return Str::camelCase([$this->metadata->name, $this->id()]);
    }

    public function queryType(): MainQueryType
    {
        return $this->queryType;
    }

    public function bind(array $config): SourceInterface
    {
        // Remove empty values bue leave 0
        $config = array_filter($config, fn ($value) => $value !== '' || $value === 0);
        $this->config = $config;
        $this->id = $this->idFromConfig($config);

        return $this;
    }

    protected function idFromConfig(array $config): string
    {
        return $config['id'] ?? '';
    }

    protected function metadataFile(): string
    {
        $basePath = new \ReflectionObject($this);
        $dir = dirname($basePath->getFileName());

        return Path::resolve($dir . '/' . $this->configFile);
    }
}
