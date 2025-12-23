<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Dynamic\Listener;

use YOOtheme\Arr;
use YOOtheme\GraphQL\Type\Definition\FieldDefinition;

class ExtendSources
{
    public function metadata($metadata, $type)
    {
        if (!($type instanceof FieldDefinition)) {
            return $metadata;
        }

        $filters = $metadata['filters'] ?? [];

        if (in_array('date', $filters) && !in_array('datemodify', $filters)) {
            $metadata['filters'] = array_merge($filters, ['datemodify']);
        }

        if (in_array('limit', $filters) && !in_array('preserve', $filters)) {
            $metadata['filters'] = array_merge($filters, ['preserve']);
        }

        if ($type->name === 'site') {
            $metadata['description'] = 'General fields of the site and the current logged in user';
        }

        if ($type->name === 'file') {
            $metadata['description'] = 'A local file fetched by pattern';
            $this->setFieldSource($metadata, ['pattern', 'offset']);
        }

        if ($type->name === 'files') {
            $metadata['description'] = 'List of local files fetched by pattern';
            $this->setFieldSource($metadata, ['pattern', '_offset.fields.offset', '_offset.fields.limit']);
        }

        return $metadata;
    }

    protected function setFieldSource(&$metadata, $fields)
    {
        foreach ($fields as $field) {
            if (Arr::has($metadata, "fields.{$field}")) {
                Arr::set($metadata, "fields.{$field}.source", true);
            }
        }
    }
}
