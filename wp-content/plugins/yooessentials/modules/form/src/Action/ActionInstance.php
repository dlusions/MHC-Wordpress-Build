<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action;

use function YOOtheme\app;
use YOOtheme\Str;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Access\AccessTransform;
use ZOOlanders\YOOessentials\Dynamic\SourceResolverManager;
use ZOOlanders\YOOessentials\Form\FormService;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;
use ZOOlanders\YOOessentials\HasLocalConfig;

class ActionInstance
{
    use HasLocalConfig;

    private string $id;

    private Action $action;

    private array $configSource;

    private AccessTransform $accessTransform;

    private SourceResolverManager $sourceResolver;

    public function __construct(Action $action, array $config, ?string $id = null)
    {
        // todo: move to UI with a 'ai_' prefix
        $this->id = $id ?? uniqid();

        $this->action = $action;
        $this->configSource = $config;
        $this->accessTransform = app(AccessTransform::class);
        $this->sourceResolver = app(SourceResolverManager::class);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function action(): Action
    {
        return $this->action;
    }

    public function __invoke(FormSubmissionResponse $response, callable $next): FormSubmissionResponse
    {
        $this->config = $this->resolveConfig($response->submission()->data());

        if (!$this->shouldRun()) {
            return $next(
                $response->withDatalog([
                    "skipped-action:{$this->action()->name()}_{$this->id()}" => $this->config(),
                ])
            );
        }

        // This is not ideal, the invocation should have `Response` and `Config` in the __invoke method,
        // and not have to take care of Local configuration
        // However this would be a breaking change, so this is the only way I can see to make this work.
        $config = array_merge(['action_id' => $this->id()], $this->config());

        return $this->action()->setConfig($config)($response, $next);
    }

    public function shouldRun(): bool
    {
        $status = $this->config('status') ?? '';

        if ($status === 'disabled') {
            return false;
        }

        return call_user_func($this->accessTransform, (object) [
            'props' => $this->config
        ]);
    }

    public function resolveConfig(array $data = []): array
    {
        $config = $this->configSource;
        $config = $this->resolveConfigDynamicFields($config, $data);
        $config = $this->resolveConfigPlaceholders($config, $data);

        if ($this->action() instanceof CanResolveConfig) {
            $config = $this->action()->resolveConfig(
                $this->configSource,
                $config,
                $data
            );
        }

        return $config['props'];
    }

    private function resolveConfigPlaceholders(array $config, array $data): array
    {
        foreach ($config as $name => &$field) {
            // skip dynamic configuration
            if ($name === 'source_extended') {
                continue;
            }

            if (is_string($field)) {
                $field = FormService::parseTags($field, $data);
            }

            if (is_object($field) || is_array($field)) {
                $field = self::resolveConfigPlaceholders((array) $field, $data);
            }
        }

        return $config;
    }

    private function resolveConfigDynamicFields(array $config, array $data): array
    {
        // map data keys to camelCase as expected by gql
        foreach ($data as $name => $val) {
            unset($data[$name]);
            $data[Str::camelCase($name)] = $val;
        }

        if (isset($config['source_extended'])) {
            $config['props'] = $this->resolveDynamicContent($config, ['submissionData' => $data]);
            unset($config['source_extended']);
        }

        // resolve nested
        foreach ($config as $name => &$field) {
            if (is_object($field) || is_array($field)) {
                $field = $this->resolveConfigDynamicFields((array) $field, $data);
            }
        }

        return $config;
    }

    private function resolveDynamicContent($node, array $params = []): array
    {
        $node = self::prepareNode($node);

        Event::on('yooessentials.source.query', function ($node) use ($params) {
            $query = 'yooessentials_form_query';

            if (isset($params['submissionData']) && ($node->source->query->name ?? '') === $query) {
                $data = $params['submissionData'];

                // if a query field is set and there is an array value,
                // we can assume is a select/checbox/etc set as multi
                // and we must set it values as expected by the schema
                $field = $node->source->query->field->name ?? null;
                if (isset($data[$field]) && is_array($data[$field])) {
                    $data[$field] = array_map(fn ($opt) => ['value' => $opt], $data[$field]);
                }

                return [
                    'data' => [$query => $data],
                ];
            }
        });

        $this->sourceResolver->resolveProps($node, $params);

        return $node->props;
    }

    private static function prepareNode($node): object
    {
        $node = (object) json_decode(json_encode($node), false);
        $node->props = (array) ($node->props ?? []);

        return $node;
    }
}
