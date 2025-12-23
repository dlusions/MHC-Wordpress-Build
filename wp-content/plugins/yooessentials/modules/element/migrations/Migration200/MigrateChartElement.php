<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration200;

use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

// rename chart element props
class MigrateChartElement extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.0.11';

    public function type(): string
    {
        return 'yooessentials_chart';
    }

    public function migrateNode(object $node, array $params): void
    {
        if (isset($node->props['title']['enabled'])) {
            $node->props['title_display'] = $node->props['title']['enabled'];
            unset($node->props['title']['enabled']);
        }

        if (isset($node->props['title_fontFamily'])) {
            $node->props['title_font_family'] = $node->props['title_fontFamily'];
            unset($node->props['title_fontFamily']);
        }

        if (isset($node->props['title_fontSize'])) {
            $node->props['title_font_size'] = $node->props['title_fontSize'];
            unset($node->props['title_fontSize']);
        }

        if (isset($node->props['title_fontColor'])) {
            $node->props['title_color'] = $node->props['title_fontColor'];
            unset($node->props['title_fontColor']);
        }

        if (isset($node->props['labels_yLabel'])) {
            $node->props['scale_y_title_text'] = $node->props['labels_yLabel'];
            unset($node->props['labels_yLabel']);
        }

        if (isset($node->props['labels_xLabel'])) {
            $node->props['scale_x_title_text'] = $node->props['labels_xLabel'];
            unset($node->props['labels_xLabel']);
        }

        if (isset($node->props['labels_fontColor'])) {
            $node->props['scale_y_title_color'] = $node->props['labels_fontColor'];
            $node->props['scale_x_title_color'] = $node->props['labels_fontColor'];
            unset($node->props['labels_fontColor']);
        }

        if (isset($node->props['labels_fontFamily'])) {
            $node->props['scale_y_title_font_family'] = $node->props['labels_fontFamily'];
            $node->props['scale_x_title_font_family'] = $node->props['labels_fontFamily'];
            unset($node->props['labels_fontFamily']);
        }

        if (isset($node->props['labels_fontSize'])) {
            $node->props['scale_y_title_font_size'] = $node->props['labels_fontSize'];
            $node->props['scale_x_title_font_size'] = $node->props['labels_fontSize'];
            unset($node->props['labels_fontSize']);
        }

        if (isset($node->props['chart_beginAtZero'])) {
            $node->props['scale_y_beginAtZero'] = $node->props['chart_beginAtZero'];
            $node->props['scale_x_beginAtZero'] = $node->props['chart_beginAtZero'];
            unset($node->props['chart_beginAtZero']);
        }

        $types = [$node->props['chart_type'] ?? ''] + array_map(function ($child) {
            $props = (array) $child->props;

            return $props['dataset_type'] ?? '';
        }, $node->children ?? []);

        // horizontal bar deprecated in favor of indexAxis
        if (in_array('horizontalBar', $types)) {
            $node->props['chart_indexAxis'] = 'y';
        }

        // rename tooltips_ as tooltip_
        foreach (['displayColors', 'backgroundColor', 'borderWidth', 'borderColor'] as $key) {
            if (isset($node->props["tooltips_$key"])) {
                $node->props["tooltip_$key"] = $node->props["tooltips_$key"];
                unset($node->props["tooltips_$key"]);
            }
        }
    }
}
