<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Xml;

use ZOOlanders\YOOessentials\Source\HasDynamicFields;

class XmlResolver
{
    use HasDynamicFields;

    protected XmlSourceFile $source;

    public function __construct(XmlSourceFile $source, array $args = [], array $root = [])
    {
        $this->source = $source;
    }

    public function resolve(): array
    {
        $data = $this->source->xml()->toArray();

        return $this->encodeFieldName($data);
    }

    private function encodeFieldName(array $data): array
    {
        $fields = [];
        foreach ($data as $header => $field) {
            if (is_array($field)) {
                $field = $this->encodeFieldName($field);
            }

            if (is_numeric($header)) {
                $fields[$header] = $field;

                continue;
            }

            $fields[self::encodeField($header)] = $field;
        }

        return $fields;
    }

    public function source(): XmlSourceFile
    {
        return $this->source;
    }
}
