<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Debug;

use function YOOtheme\app;
use YOOtheme\Arr;
use YOOtheme\Config;
use YOOtheme\Metadata;
use YOOtheme\Str;

class ConsoleLogger
{
    protected static $LOGS = [];

    public static function info(Config $config, $log)
    {
        self::log($config, array_merge(['type' => 'info', 'group' => 'YOOessentials'], $log));

        return false;
    }

    public static function warn(Config $config, $log)
    {
        self::log($config, array_merge(['type' => 'warn'], $log));

        return false;
    }

    public static function error(Config $config, $log)
    {
        self::log($config, array_merge(['type' => 'error'], $log));

        return false;
    }

    public static function sourceError(Metadata $metadata, Config $config, $log)
    {
        $error = [
            'error' => $log[0]->getMessage(),
            'path' => $log[0]->getPath(),
            'trace' => $log[0]->getPrevious() ? $log[0]->getPrevious()->getTraceAsString() : $log[0]->getTraceAsString(),
        ];

        if ($config->get('app.debug') || $config->get('app.isCustomizer')) {
            $metadata->set('script:graphql-extended-error', 'console.warn(' . json_encode($error) . ');');
        }

        return false;
    }

    public static function print(Metadata $metadata)
    {
        if (empty(self::$LOGS)) {
            return;
        }

        $script = self::buildPrintScript(self::$LOGS);
        $metadata->set('script:yooessentials-logs', $script);
    }

    public static function alert(Metadata $metadata)
    {
        $errors = array_filter(self::$LOGS, fn ($log) => $log['type'] === 'error');

        if (empty($errors)) {
            return;
        }

        $script = self::buildAlertScripts($errors);
        $metadata->set('script:yooessentials-logs-alert', $script);
    }

    protected static function log(Config $config, array $log)
    {
        if ($config->get('app.debug') || $config->get('app.isCustomizer')) {
            self::$LOGS[] = $log;
        }
    }

    /**
     * Function that groups an array of associative arrays by some key.
     */
    protected static function groupBy(array $data, string $key)
    {
        $result = [];

        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[''][] = $val;
            }
        }

        return $result;
    }

    protected static function buildPrintScript(array $logs): ?string
    {
        $script = '';

        foreach (self::groupBy(self::$LOGS, 'group') as $group => $logs) {
            if ($group) {
                $script .= "console.groupCollapsed('$group');";
            }

            foreach ($logs as $log) {
                $script .= sprintf(
                    "console.%s('%s', %s);",
                    $log['type'],
                    $log['label'] ?? Str::upperFirst($log['type']),
                    json_encode(Arr::omit($log, ['type', 'label', 'group']))
                );
            }

            if ($group) {
                $script .= 'console.groupEnd();';
            }
        }

        return $script;
    }

    protected static function buildAlertScripts(array $logs): ?string
    {
        $modalContent = [];

        foreach ($logs as $log) {
            $errorRef = $log['error'] ?? '';
            $errorDump = Arr::omit($log, ['error', 'type', 'group', 'exception']);

            if (isset($log['exception'])) {
                $errorDump['trace'] = $log['exception']->getTraceAsString();

                // anonymize
                $errorDump['trace'] = str_replace(app()->config->get('app.rootDir'), '', $errorDump['trace']);
                $errorDump['trace'] = str_replace(app()->config->get('server.HOME'), '', $errorDump['trace']);
            }

            $errorDump = json_encode($errorDump, JSON_PRETTY_PRINT);
            $errorDump = trim(str_replace('`', '\'', $errorDump));

            $script = '<p class="uk-text-danger uk-overflow-auto">%s</p>
                <div class="uk-overflow-auto">
                    <pre class="uk-background-muted uk-height-small">%s</pre>
                </div>';

            $modalContent[] = sprintf($script, $errorRef, $errorDump);
        }

        return 'document.addEventListener(\'DOMContentLoaded\', function() {
            UIkit.modal.dialog(`
                <div class="uk-modal-header">
                    <h3 class="uk-modal-title">Execution Error</h3>
                    <p>There was a PHP error triggered while executing <span class="uk-text-bold">yooessentials</span> plugin.</p>
                </div>
                <div class="uk-modal-body" uk-overflow-auto>
                    ' . implode('', $modalContent) . '
                </div>
                <div class="uk-modal-footer uk-flex uk-flex-between uk-flex-middle">
                    <span class="uk-text-meta">Notice that you are viewing this because you are logged in as an admin.</span>
                    <button class="uk-button uk-button-primary uk-margin-large-left uk-modal-close" type="button">OK</button>
                </div>`);
        });';
    }
}
