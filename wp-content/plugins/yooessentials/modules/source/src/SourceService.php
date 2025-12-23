<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source;

use function YOOtheme\app;
use YOOtheme\Event;
use YOOtheme\Builder\Source;
use YOOtheme\GraphQL\Type\Definition\ObjectType;
use ZOOlanders\YOOessentials\Config\Config;
use ZOOlanders\YOOessentials\Source\GraphQL\TypeInterface;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

class SourceService
{
    public const SOURCES_CONFIG_KEY = 'source.sources';

    protected bool $enabled = true;

    protected array $providers = [];

    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function sourceConfigs(): array
    {
        $configs = $this->config->get(self::SOURCES_CONFIG_KEY, []);

        // map sourceType to provider, legacy?
        foreach ($configs as &$config) {
            $config['provider'] = $config['sourceType'] ?? $config['provider'];
        }

        // filter out sources without provider
        $configs = array_filter($configs, fn ($config) => !empty($config['provider']));

        return $configs;
    }

    public static function resolve($item, $args, $context, $info): array
    {
        /** @var SourceService $sourceManager */
        $sourceManager = app(SourceService::class);

        try {
            $source = $sourceManager->source($args['source_id'], $args);

            return $source->resolve($args);
        } catch (\Throwable $e) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'action' => 'source-query-resolve',
                'args' => $args,
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);

            return [];
        }
    }

    public function registerSources(Source $source): void
    {
        foreach ($this->sourceProviders() as $provider => $sourceClass) {
            $sources = $this->sources($provider);

            // Group types by type + name to avoid re-registering the same type twice
            // Side advantage is to have better error handling and reporting
            $types = [];
            foreach ($sources as $config) {
                if (($config['status'] ?? '') === 'disabled') {
                    continue;
                }

                try {
                    $sourceInstance = $this->createSource($provider, $config);

                    if (count($sourceInstance->types())) {
                        $source->queryType($sourceInstance->queryType()->config());
                    }

                    /** @var TypeInterface $type */
                    foreach ($sourceInstance->types() as $type) {
                        // queries are the exception, they all must be registered
                        if ($type->type() === TypeInterface::TYPE_QUERY) {
                            $types[$type->type()][] = $type;
                        } else {
                            $types[$type->type()][$type->name()] = $type;
                        }
                    }
                } catch (\Throwable $e) {
                    Event::emit('yooessentials.error', [
                        'addon' => 'source',
                        'provider' => $provider,
                        'error' => 'Error creating source with config: ' . json_encode($config),
                        'exception' => $e,
                        'exceptionMessage' => $e->getMessage(),
                    ]);
                }
            }

            // Add Object Type
            foreach ($types[TypeInterface::TYPE_OBJECT] ?? [] as $name => $type) {
                try {
                    $source->objectType($name, $type->config());
                } catch (\Throwable $e) {
                    Event::emit('yooessentials.error', [
                        'addon' => 'source',
                        'provider' => $provider,
                        'error' => "Error creating {$type->type()} Type {$type->name()} with config " . json_encode($type->config()),
                        'exception' => $e,
                        'exceptionMessage' => $e->getMessage(),
                    ]);
                }
            }

            // Add Input Types
            foreach ($types[TypeInterface::TYPE_INPUT] ?? [] as $type) {
                try {
                    $source->inputType($type->name(), $type->config());
                } catch (\Throwable $e) {
                    Event::emit('yooessentials.error', [
                        'addon' => 'source',
                        'provider' => $provider,
                        'error' => "Error creating Input {$type->type()} Type {$type->name()} with config " . json_encode($type->config()),
                        'exception' => $e,
                        'exceptionMessage' => $e->getMessage(),
                    ]);
                }
            }

            // Add Query Types
            foreach ($types[TypeInterface::TYPE_QUERY] ?? [] as $type) {
                try {
                    $source->objectType($type->source()->queryType()->name(), $type->config());
                } catch (\Throwable $e) {
                    Event::emit('yooessentials.error', [
                        'addon' => 'source',
                        'provider' => $provider,
                        'error' => "Error creating {$type->type()} Type {$type->name()} with config :" . json_encode($type->config()),
                        'exception' => $e,
                        'exceptionMessage' => $e->getMessage(),
                    ]);
                }
            }
        }
    }

    public function setSources(array $sources): self
    {
        $this->config->set(self::SOURCES_CONFIG_KEY, $sources);

        return $this;
    }

    public function addSourceType(string $sourceType, string $sourceClass): self
    {
        if (!isset($this->providers[$sourceType])) {
            $this->providers[$sourceType] = $sourceClass;
        }

        return $this;
    }

    public function source(string $id, array $config = []): SourceInterface
    {
        $source = array_filter($this->sourceConfigs(), fn ($source) => (string) ($source['id'] ?? '') === $id);

        if (empty($source)) {
            throw new \Exception("Source Not Found: {$id}. Existing sources: " . json_encode($this->sourceConfigs()));
        }

        $source = array_shift($source);

        return $this->createSource($source['provider'], array_merge($source, $config));
    }

    /**
     * @return array[]|array
     */
    public function sources(?string $provider = null): array
    {
        if (!$provider) {
            return $this->sourceConfigs();
        }

        return array_filter($this->sourceConfigs(), fn (array $source) => $source['provider'] === $provider);
    }

    /**
     * @return string[]|array
     */
    public function sourceProviders(): array
    {
        return $this->providers;
    }

    public function createSource(string $name, array $config = []): ?SourceInterface
    {
        $class = $this->providers[$name] ?? null;

        if (!$class) {
            return null;
        }

        /** @var SourceInterface $class */
        $class = app($class);

        return $class->bind($config);
    }

    public function setObjectType(Source $source, string $name, array $config): void
    {
        $type = new ObjectType([
            'name' => $name,
            'resolveField' => [$source, 'resolveField'],
        ]);

        $type->config = $config;
        $source->setType($type);
    }
}
