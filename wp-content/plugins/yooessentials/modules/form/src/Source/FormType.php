<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Source;

use function YOOtheme\app;
use YOOtheme\Builder;
use YOOtheme\Str;

class FormType
{
    public const NAME = 'YooessentialsForm';
    public const LABEL = 'Form';

    public static function config(array $controls): array
    {
        /** @var Builder $builder */
        $builder = app(Builder::class);

        $fields = [];
        foreach ($controls as $control) {
            $type = $builder->types[$control['type']] ?? null;
            $name = $control['props']['control_name'] ?? '';
            $label = $control['props']['control_label'] ?? '';
            $multiple = $control['props']['control_multiple'] ?? false;

            if (!$name || !$type || !$type->submittable) {
                continue;
            }

            $isListOf = $type->container;
            $typeName = str_replace('yooessentials_form_', '', $type->name);

            if (($typeName === 'upload' || $typeName === 'select') && $multiple) {
                $isListOf = true;
            }

            $fields[Str::camelCase($name)] = [
                'type' => $isListOf
                    ? ['listOf' => FormOptionType::NAME]
                    : 'String',
                'metadata' => [
                    'label' => $label ?: Str::titleCase($name),
                    'es_field_meta' => ['control' => $name],
                    'filters' => [
                        Str::contains($typeName, 'date') ? 'date' : null,
                        Str::contains($typeName, 'text') ? 'limit' : null,
                    ],
                ],
            ];
        }

        return [
            'fields' => $fields,
            'metadata' => [
                'type' => true,
                'label' => self::LABEL,
            ],
        ];
    }
}
