<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver;

use function YOOtheme\app;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Source\SourceService;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

trait LoadsSourceFromArgs
{
    public static function loadSource(array $args, string $sourceClass): ?SourceInterface
    {
        $sourceId = $args['source_id'] ?? null;

        /** @var SourceService $sourceManager */
        $sourceManager = app(SourceService::class);

        try {
            return $sourceId ? $sourceManager->source($sourceId, $args) : new $sourceClass($args);
        } catch (\Throwable $e) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'action' => 'source-load',
                'args' => $args,
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);

            return null;
        }
    }
}
