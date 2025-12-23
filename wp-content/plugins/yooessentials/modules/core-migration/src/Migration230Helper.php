<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration;

abstract class Migration230Helper
{
    public static function iterateNodeSources(object $node, callable $cb)
    {
        self::iterateSource($node->source ?? null, $cb);

        foreach ((array) ($node->source_extended->props ?? []) as $prop) {
            self::iterateSource($prop, $cb);
        }

        self::iterateAccessConditions(
            $node,
            fn ($condition) => self::iterateNodeSources((object) $condition, $cb)
        );

        self::iterateFormActions(
            $node,
            fn ($action) => self::iterateNodeSources((object) $action, $cb)
        );
    }

    public static function iterateAccessConditions(object $node, callable $cb)
    {
        $props = (array) ($node->props ?? []);
        $conditions = (array) ($props['yooessentials_access_conditions'] ?? []);

        foreach ($conditions as $condition) {
            $cb($condition);
        }
    }

    public static function iterateFormActions(object $node, callable $cb)
    {
        $props = (array) ($node->props ?? []);
        $form = $props['yooessentials_form'] ?? null;

        if (!$form) {
            return;
        }

        $actions = (array) ($form->after_submit_actions ?? []);

        foreach ($actions as $action) {
            $cb($action);
        }
    }

    protected static function iterateSource($source, callable $cb): void
    {
        if (!is_object($source)) {
            return;
        }

        $cb($source);

        // iterate over extended arguments
        foreach ((array) ($source->query->arguments_extended ?? []) as $source) {
            self::iterateSource($source, $cb);
        }

        // iterate over composed sources
        foreach ((array) ($source->composed->sources ?? []) as $source) {
            self::iterateSource($source, $cb);
        }

        // iterate query conditions
        foreach (['filters', 'ordering'] as $arg) {
            $conditions = (array) ($source->query->arguments->{$arg} ?? []);

            foreach ($conditions as $condition) {
                self::iterateNodeSources((object) $condition, $cb);
            }
        }
    }
}
