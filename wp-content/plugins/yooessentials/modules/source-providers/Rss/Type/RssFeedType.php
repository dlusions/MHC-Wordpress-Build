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
use ZOOlanders\YOOessentials\Source\Provider\Xml\Type\ExtractsFields;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

class RssFeedType extends GenericType implements HasSourceInterface
{
    use ExtractsFields, HasSource;

    private ?RssFeedItemType $itemType = null;

    public const LABEL = 'Feed';

    public function __construct(SourceInterface $source)
    {
        $this->source = $source;
        $this->getFields($this->source->rss()->toArray());
    }

    public function itemType(): RssFeedItemType
    {
        if ($this->itemType !== null) {
            return $this->itemType;
        }

        $this->itemType = new RssFeedItemType($this->source->rss()->items(), $this->source());

        return $this->itemType;
    }

    public function name(): string
    {
        return Str::camelCase([$this->source->queryName(), 'Feed'], true);
        // return self::encodeField('RSSfeed_' . $this->source->id());
    }

    public function config(): array
    {
        $data = $this->source->rss()->toArray();

        $fields = $this->getFields($data);

        return [
            'fields' => $fields,
            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    public static function resolveDateTime($data, $args)
    {
        $date = $data[$args['header']] ?? null;

        return $date ? $date->format('U') : null;
    }
}
