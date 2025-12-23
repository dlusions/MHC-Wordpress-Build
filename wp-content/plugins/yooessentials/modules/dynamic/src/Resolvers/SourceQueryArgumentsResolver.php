<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Dynamic\Resolvers;

use function YOOtheme\app;
use YOOtheme\Builder\Source;
use ZOOlanders\YOOessentials\Dynamic\SourceResolverAbstract;

class SourceQueryArgumentsResolver extends SourceResolverAbstract
{
    public function preload(object $source, object $node, array $params = [])
    {
        foreach ((array) ($source->query->arguments_extended ?? []) as $src) {
            $this->manager->preload((object) $src, $node, $params);
        }
    }

    public function prerender(object $source, object $node, array $params = [])
    {
        $this->resolve($source, $node, $params);
    }

    public function resolve(object $source, object $node, array $params = [])
    {
        if (isset($source->query->arguments_extended)) {
            $this->resolveQueryArguments($source->query, $node, $params);
        }

        if (isset($source->query->field->arguments_extended)) {
            $this->resolveQueryFieldArguments($source->query, $node, $params);
        }
    }

    protected function resolveQueryArguments(object $query, object $node, array $params = []): void
    {
        $argsTypes = $this->findQueryArgs($query->name);

        $data = $this->resolveArguments((array) ($query->arguments_extended ?? []), $argsTypes, $node, $params);

        // ensure arguments is an object
        $query->arguments = (object) ($query->arguments ?? []);

        foreach ($data as $key => $value) {
            $query->arguments->{$key} = $value;
        }
    }

    protected function resolveQueryFieldArguments(object $query, object $node, array $params = []): void
    {
        $argsTypes = $this->findQueryArgs("{$query->name}.{$query->field->name}");
        $data = $this->resolveArguments((array) ($query->field->arguments_extended ?? []), $argsTypes, $node, $params);

        // ensure arguments is an object
        $query->field->arguments = (object) $query->field->arguments;

        foreach ($data as $key => $value) {
            $query->field->arguments->{$key} = $value;
        }

        unset($query->field->arguments_extended);
    }

    protected function resolveArguments(array $args, array $argsTypes, object $node, array $params = []): array
    {
        $data = [];

        foreach ($args as $key => $source) {
            $argType = $argsTypes[$key]['type']->name ?? null;

            $data[$key] = $this->manager->resolve($source, $node, $params);

            if ($argType && !is_array($data[$key])) {
                $data[$key] = self::enforceType($argType, $data[$key]);
            }
        }

        return $data;
    }

    protected function findQueryArgs(string $name)
    {
        $schema = app(Source::class)->getSchema();
        $fields = $schema->getQueryType()->getFields();

        $path = explode('.', $name);

        if (count($path) === 2) {
            $fields = $fields[$path[0]]->config['type']->config['fields'] ?? [];

            if (is_callable($fields)) {
                $fields = $fields();
            }

            $fields = (array) ($fields[$path[1]] ?? []);
        } else {
            $fields = (array) ($fields[$name] ?? []);
        }

        return $fields['args'] ?? $fields['config']['args'] ?? [];
    }

    protected function enforceType(string $type, $value)
    {
        switch ($type) {
            case 'Int':
                return (int) $value;
            case 'String':
                return (string) $value;
        }

        return null;
    }
}
