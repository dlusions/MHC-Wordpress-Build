<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Element\Chart;

use function YOOtheme\app;
use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Util\Prop;

abstract class ChartHelper
{
    const chartDefaults = [
        'indexAxis' => 'x',
        'maintainAspectRatio' => true,
        'layout' => [
            'padding' => '',
            'autoPadding' => true,
        ],
        'title' => [
            'align' => 'center',
            'display' => false,
            'fullSize' => true,
            'position' => 'top',
            'padding' => 10,
            'color' => '',
        ],
        'subtitle' => [
            'align' => 'center',
            'display' => false,
            'fullSize' => true,
            'position' => 'top',
            'padding' => 10,
        ],
        'legend' => [
            'display' => true,
        ],
        'tooltip' => [
            'enabled' => true,
            'displayColors' => true,
            'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
            'borderColor' => 'rgba(0, 0, 0, 0)',
            'borderWidth' => 0,
            'padding' => 6,
        ],
        'animation' => [
            'duration' => 1000,
            'easing' => 'easeOutQuart',
            'delay' => null,
            'loop' => null,
        ],
        'scale' => [
            'type' => '',
            'display' => true,
            'reverse' => false,
            'stacked' => false,
            'beginAtZero' => true,
            'min' => '',
            'max' => '',
            'suggestedMin' => '',
            'suggestedMax' => '',
        ],
        'grid' => [
            'display' => true,
            'lineWidth' => 1,
            'color' => '',
        ],
        'ticks' => [
            'display' => true,
            'color' => '',
        ],
        'dataset' => [
            'type' => '',
            'fill' => false,
            'showLine' => true,
            'lineTension' => 0,
        ],
        'font' => [
            'size' => 12,
            'family' => "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
        ],
    ];

    public static function generateConfig(object $node)
    {
        $labels = [];
        $datasets = [];

        $props = self::parseProps($node->props);

        // generate datasets and labels
        foreach ($node->children as $datasetNode) {
            $dataset = Prop::filterByPrefix($datasetNode->props, 'dataset_');
            $dataset = self::filterOutDefaults($dataset, 'dataset');
            $dataset = (object) $dataset;

            $borderColor = $dataset->borderColor ?? null;
            $backgroundColor = $dataset->backgroundColor ?? null;

            $dataset->data = [];
            $dataset->metadata = [];
            $dataset->borderColor = [];
            $dataset->backgroundColor = [];

            $dataset->label ??= '';

            // process data nodes
            foreach ($datasetNode->children ?? [] as $dataNode) {
                $label = trim($dataNode->props['label'] ?? '');
                $labels[] = $label;

                $dataset->data[$label] = (float) $dataNode->props['data'];
                $dataset->metadata[$label] = $dataNode->props['metadata'];

                $dataset->borderColorFallback = $borderColor;
                $dataset->backgroundColorFallback = $backgroundColor;

                $dataset->borderColor[$label] = $dataNode->props['borderColor'] ?? $borderColor;
                $dataset->backgroundColor[$label] = $dataNode->props['backgroundColor'] ?? $backgroundColor;
            }

            $datasets[] = $dataset;
        }

        // cleanup labels
        $labels = array_values(array_unique($labels));

        // order datasets data by label filling with null missing values
        foreach ($datasets as &$dataset) {
            $dataset->data = array_map(fn ($label) => $dataset->data[$label] ?? null, $labels);
            $dataset->metadata = array_map(fn ($label) => $dataset->metadata[$label] ?? null, $labels);

            // if metadata is empty, remove it
            if (empty(array_filter($dataset->metadata))) {
                unset($dataset->metadata);
            }
        }

        // order datasets colors by label filling with null missing values
        foreach ($datasets as &$dataset) {
            $dataset->borderColor = array_map(fn ($label) => $dataset->borderColor[$label] ?? $dataset->borderColorFallback, $labels);
            $dataset->backgroundColor = array_map(fn ($label) => $dataset->backgroundColor[$label] ?? $dataset->backgroundColorFallback, $labels);

            // remove if empty, singlefy if not
            if (empty(array_filter($dataset->borderColor))) {
                unset($dataset->borderColor);
            } else {
                $dataset->borderColor = self::singlefy($dataset->borderColor);
            }

            if (empty(array_filter($dataset->backgroundColor))) {
                unset($dataset->backgroundColor);
            } else {
                $dataset->backgroundColor = self::singlefy($dataset->backgroundColor);
            }

            unset($dataset->borderColorFallback);
            unset($dataset->backgroundColorFallback);
        }

        $config = [
            'type' => $props['chart']['type'] ?? 'line',
            'data' => ['labels' => $labels, 'datasets' => $datasets],
            'debug' => app()->config->get('app.debug'),
            'options' => self::filterEmpty([
                'indexAxis' => $props['chart']['indexAxis'] ?? null,
                'maintainAspectRatio' => $props['chart']['maintainAspectRatio'] ?? null,
                'scales' => self::filterEmpty([
                    'y' => self::getScaleSettings($props, 'y'),
                    'x' => self::getScaleSettings($props, 'x'),
                ]),
                'plugins' => self::filterEmpty([
                    'title' => self::getPluginSettings($props, 'title'),
                    'subtitle' => self::getPluginSettings($props, 'subtitle'),
                    'legend' => self::getPluginSettings($props, 'legend'),
                    'tooltip' => self::getPluginSettings($props, 'tooltip'),
                ]),
                'layout' => self::getLayoutSettings($props),
                'animation' => self::getAnimationSettings($props),
            ]),
        ];

        if ($props['chart']['deferred'] ?? false) {
            $config['options']['plugins']['deferred'] = [
                'yOffset' => '30%', // defer until 50% of the canvas height are inside the viewport
                'delay' => 500, // delay of 500 ms after the canvas is considered inside the viewport
            ];
        }

        return $config;
    }

    protected static function getScaleSettings(array $props, string $scale)
    {
        $props = self::filterOutDefaults($props['scale'][$scale] ?? [], 'scale');

        if (($props['display'] ?? null) === false) {
            return ['display' => false];
        }

        if (isset($props['grid'])) {
            $props['grid'] = self::filterOutDefaults($props['grid'], 'grid');
        }

        if (isset($props['ticks'])) {
            $props['ticks'] = self::filterOutDefaults($props['ticks'], 'ticks');
        }

        if (isset($props['title'])) {
            $props['title'] = self::filterOutDefaults($props['title'], 'title');

            if (isset($props['title']['font'])) {
                $props['title']['font'] = self::filterOutDefaults($props['title']['font'], 'font');
            }
        }

        return self::filterEmpty($props);
    }

    protected static function getLayoutSettings(array $props)
    {
        $props = self::filterOutDefaults($props['layout'] ?? [], 'layout');

        if (isset($props['padding'])) {
            $props['padding'] = self::spreadPadding($props['padding']);
        }

        return $props;
    }

    protected static function getAnimationSettings(array $props)
    {
        $props = self::filterOutDefaults($props['chart']['animation'] ?? [], 'animation');

        if (($props['enabled'] ?? null) === false) {
            return false;
        }

        return $props;
    }

    protected static function getPluginSettings(array $props, string $name)
    {
        $props = self::filterOutDefaults($props[$name] ?? [], $name);

        if (($props['display'] ?? null) === false) {
            return ['display' => false];
        }

        if (($props['enabled'] ?? null) === false) {
            return ['enabled' => false];
        }

        if (isset($props['title']['font'])) {
            $props['title']['font'] = self::filterOutDefaults($props['title']['font'], 'font');
        }

        return self::filterEmpty($props);
    }

    protected static function filterOutDefaults(array $props, string $name)
    {
        return self::filterEmpty(array_diff_assoc($props, self::chartDefaults[$name]));
    }

    protected static function filterEmpty(array $values)
    {
        return Arr::filter($values, fn ($v) => is_array($v) ? count($v) : !is_null($v));
    }

    protected static function singlefy($v)
    {
        if (($unique = array_unique(array_filter($v))) and count($unique) === 1) {
            return array_shift($unique);
        }

        return $v;
    }

    protected static function parseProps(array $props)
    {
        $result = [];

        $props = array_filter($props, fn ($prop) => $prop !== null);

        foreach ($props as $key => $value) {
            $parts = array_values(array_filter(explode('_', $key)));

            $node = &$result;
            foreach ($parts as $part) {
                if (!is_string($part)) {
                    continue;
                }
                if (!isset($node[$part])) {
                    $node[$part] = [];
                }
                $node = &$node[$part];
            }
            $node = $value;
        }

        return $result;
    }

    protected static function spreadPadding(string $padding)
    {
        $padding = str_replace(' ', ',', trim($padding));
        $padding = explode(',', $padding);

        switch (count($padding)) {
            case 1:
                return $padding[0];
            case 2:
                return [
                    'top' => $padding[0],
                    'right' => $padding[1],
                    'bottom' => $padding[0],
                    'left' => $padding[1],
                ];
            case 3:
                return [
                    'top' => $padding[0],
                    'right' => $padding[1],
                    'bottom' => $padding[2],
                    'left' => $padding[1],
                ];
            case 4:
                return [
                    'top' => $padding[0],
                    'right' => $padding[1],
                    'bottom' => $padding[2],
                    'left' => $padding[3],
                ];
        }

        return null;
    }
}
