<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Dynamic;

use YOOtheme\Builder;
use YOOtheme\Builder\BuilderConfig;
use YOOtheme\Builder\Source\SourceTransform;
use ZOOlanders\YOOessentials\Migration;
use ZOOlanders\YOOessentials\Util;

return [
    'config' => [
        BuilderConfig::class => __DIR__ . '/config/builder.json',
    ],

    'routes' => [
        ['post', GlobalQueryController::PRESAVE_QUERY_ENDPOINT, GlobalQueryController::class . '@presave']
    ],

    'events' => [
        'source.init' => [
            Listener\ExtendSourcesJoomla::class => ['@source', -100],
            Listener\ExtendSourcesWordPress::class => ['@source', -100],
        ],

        'source.type.metadata' => [
            Listener\ExtendSources::class => '@metadata',
            Listener\ExtendSourcesJoomla::class => '@metadata',
            Listener\ExtendSourcesWordPress::class => '@metadata',
        ],

        'customizer.init' => [
            Listener\LoadCustomizerData::class => ['@handle', 10],
        ],

        'yooessentials.config.load' => [
            Listener\ValidateGlobalQueries::class => ['@load'],
        ],
    ],

    'extend' => [
        Builder::class => function (Builder $builder, $app) {
            $sourceNode = $app(SourceNodeTransform::class);
            $sourceProps = $app(SourcePropsTransform::class);

            // preload SourceTransform index
            $index = Util\Arr::array_find_key($builder->transforms['preload'], fn ($t) => Util\Misc::getCallableClassName($t) === SourceTransform::class);

            $builder->addTransform('preload', [$sourceNode, 'preload'], $index + 1); // after SourceTransform
            $builder->addTransform('preload', [$sourceProps, 'preload'], $index + 2); // after SourceTransform

            // prerender SourceTransform index
            $index = Util\Arr::array_find_key($builder->transforms['prerender'], fn ($t) => Util\Misc::getCallableClassName($t) === SourceTransform::class);

            $builder->addTransform('prerender', [$sourceNode, 'prerender'], $index); // before SourceTransform
            $builder->addTransform('prerender', [$sourceProps, 'prerender'], $index + 2); // after SourceTransforms
        },

        SourceTransform::class => function (SourceTransform $transform) {
            $index = array_search('date', array_keys($transform->filters));

            $transform->addFilter('time', [SourceFilter::class, 'applyTime'], $index + 1);
            $transform->addFilter('datemodify', [SourceFilter::class, 'applyDatemodify'], $index);
        },

        SourceResolverManager::class => function (SourceResolverManager $manager, $app) {
            $manager->addResolver($app(Resolvers\NodeSourceResolver::class));
            $manager->addResolver($app(Resolvers\ParentSourceResolver::class));
            $manager->addResolver($app(Resolvers\ComposedSourceResolver::class));
            $manager->addResolver($app(Resolvers\GlobalSourceQueryResolver::class));
            $manager->addResolver($app(Resolvers\AdjacentSourceResolver::class));
            $manager->addResolver($app(Resolvers\SourceQueryArgumentsResolver::class));
            $manager->addResolver($app(Resolvers\SourceQueryResolver::class));
        },
    ],

    'yooessentials-migrations' => [
        Migration\Migration170\MigrateDynamicFilters::class,
        Migration\Migration170\MigrateDynamicQueries::class,
        Migration\Migration200\MigrateDynamicKeys::class,
        Migration\Migration220\RemoveDuplicatedDynamicQueries::class,
        Migration\Migration230\ReplaceDynamicClosestWithParent::class,
    ],

    'services' => [
        SourceResolverManager::class => '',
    ],
];
