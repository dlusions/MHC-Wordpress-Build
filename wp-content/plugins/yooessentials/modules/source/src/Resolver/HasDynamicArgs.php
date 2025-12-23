<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver;

use ZOOlanders\YOOessentials\Dynamic\SourceResolverManager;
use function YOOtheme\app;

trait HasDynamicArgs
{
    // TODO: remove this workflow
    // this entire workflow is now unnecessary,
    // keeping it intact as caution until next major cleanup
    public static function resolveDynamicArguments($node, array $params = []): array
    {
        /** @var SourceResolverManager $sourceResolver */
        $sourceResolver = app(SourceResolverManager::class);
        $node = (object) $node;

        if (isset($node->source)) {
            $node->source_extended = json_decode($node->source);
            $sourceResolver->resolveProps($node, $params);
        }

        return (array) ($node->props ?? ($node ?? []));
    }
}
