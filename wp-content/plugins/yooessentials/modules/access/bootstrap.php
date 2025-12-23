<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Access;

use YOOtheme\Builder;
use YOOtheme\Builder\Source\SourceTransform;
use ZOOlanders\YOOessentials\Migration;
use ZOOlanders\YOOessentials\Util;

require_once __DIR__ . '/class_aliases.php';

return [
    'events' => [
        'customizer.init' => [
            Listener\LoadCustomizerData::class => ['@handle', 10],
        ],

        'builder.type' => [
            Listener\ExtendBuilderType::class => ['@handle', -10],
        ],
    ],

    'extend' => [
        Builder::class => function (Builder $builder, $app) {
            $source = $app(SourceAccessTransform::class);

            // preload SourceTransform index
            $index = Util\Arr::array_find_key($builder->transforms['preload'], fn ($t) => Util\Misc::getCallableClassName($t) === SourceTransform::class);

            $builder->addTransform('preload', [$source, 'preload'], $index + 1); // after SourceTransform
            $builder->addTransform('prerender', [$source, 'prerender']); // last
            $builder->addTransform('render', new AccessTransform()); // last
        },
    ],

    'loaders' => [
        'yooessentials-access-rules' => new LegacyRuleLoader(),
    ],

    'yooessentials-migrations' => [
        Migration\Migration140\MigrateAccessRulesNamespace::class,
    ]
];
