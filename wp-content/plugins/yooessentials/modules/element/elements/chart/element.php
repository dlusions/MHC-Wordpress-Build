<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Element\Chart;

require_once __DIR__ . '/helper.php';

use function YOOtheme\app;
use YOOtheme\Metadata;

return [
    'transforms' => [
        'render' => function ($node) {
            /**
             * @var Metadata $metadata
             */
            $metadata = app(Metadata::class);

            $metadata->set('script:yooessentials-chart', [
                'src' => '~yooessentials_url/modules/element/elements/chart/chart.min.js',
                'defer' => true,
            ]);

            $metadata->set('style:yooessentials-chart', [
                'href' => '~yooessentials_url/modules/element/elements/chart/chart.min.css',
            ]);

            $node->config = ChartHelper::generateConfig($node);
            $node->debug = app()->config->get('app.debug');
        },
    ],
];
