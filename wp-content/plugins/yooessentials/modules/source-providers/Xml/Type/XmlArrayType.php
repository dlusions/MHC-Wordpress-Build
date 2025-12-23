<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Xml\Type;

use YOOtheme\Str;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class XmlArrayType extends GenericType
{
    use ExtractsFields;

    private string $prefix;
    private string $header;
    private array $data;
    private string $dataPrefix;
    private array $dataTypes;

    public function __construct(string $header, array $data, string $prefix, array $dataTypes, string $dataPrefix)
    {
        $this->data = $data;
        $this->header = $header;
        $this->prefix = $prefix;
        $this->dataPrefix = $dataPrefix;
        $this->dataTypes = $dataTypes;

        $this->getFields($data, $dataPrefix);
    }

    public function name(): string
    {
        return Str::camelCase([$this->prefix, self::encodeField($this->header)], true);
        // return self::encodeField($this->prefix . '_' . $this->header);
    }

    public function label(): string
    {
        return Str::titleCase($this->header);
    }

    public function config(): array
    {
        $fields = $this->getFields($this->data);

        return [
            'fields' => $fields,
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }
}
