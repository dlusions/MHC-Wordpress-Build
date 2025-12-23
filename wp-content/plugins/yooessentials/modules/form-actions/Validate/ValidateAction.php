<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Validate;

use ZOOlanders\YOOessentials\Condition\ConditionResolver;
use ZOOlanders\YOOessentials\Condition\ConditionManager;
use ZOOlanders\YOOessentials\Form\Action\CanResolveConfig;
use ZOOlanders\YOOessentials\Form\Action\StandardAction;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;
use ZOOlanders\YOOessentials\Debug\Logger;
use ZOOlanders\YOOessentials\Util;

class ValidateAction extends StandardAction implements CanResolveConfig
{
    public const NAME = 'validate';

    public function __invoke(FormSubmissionResponse $response, callable $next): FormSubmissionResponse
    {
        $config = $this->getConfig();
        $validation = Util\Prop::filterByPrefix($config, 'validation_');

        if (empty($validation['conditions'])) {
            return $next($response);
        }

        $conditions = array_map(fn ($condition) => (object) $condition, $validation['conditions']);

        $query = $validation['mode'] ?? null;

        if ($query === ConditionManager::MODE_CUSTOM) {
            $query = $validation['mode_query'] ?? null;
        }

        if (!$query) {
            $query = ConditionManager::MODE_AND;
        }

        /** @var Logger $logger */
        $logger = new Logger('access');

        /** @var ConditionResolver $resolver */
        $resolver = (new ConditionResolver($conditions))->withQuery($query)->withLogger($logger);

        $valid = $resolver->resolve(new \stdClass(), []);

        if (!$valid) {
            return $response->withErrors([$config['message'] ?? 'Validation Error']);
        }

        return $next($response);
    }

    public function resolveConfig(array $configSource, array $resolvedConfig, array $data = []): array
    {
        // Fix query mode placeholders being removed by generic placeholder manipulation
        $resolvedConfig['props']['validation_mode_query'] = $configSource['props']['validation_mode_query'] ?? null;

        return $resolvedConfig;
    }
}
