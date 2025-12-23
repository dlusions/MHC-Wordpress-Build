<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Rss\Type;

use YOOtheme\Str;
use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Source\GraphQL\HasSource;
use ZOOlanders\YOOessentials\Source\GraphQL\HasSourceInterface;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;
use ZOOlanders\YOOessentials\Source\Provider\Xml\Type\ExtractsFields;

class RssFeedItemType extends GenericType implements HasSourceInterface
{
    use ExtractsFields, HasSource;

    protected string $prefix = '';

    protected array $data = [];

    public const LABEL = 'Item';

    public function __construct(array $items, SourceInterface $source)
    {
        $return = [];
        array_walk($items, function ($a) use (&$return) {
            foreach ($a as $k => $v) {
                $return[$k] = $v;
            }
        });

        $this->data = $return;
        $this->source = $source;

        $this->getFields($this->data);
    }

    public function types(): array
    {
        return $this->types;
    }

    public function name(): string
    {
        return Str::camelCase([$this->source->queryName(), 'Item'], true);
        // return self::encodeField($this->prefix . '_item');
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
