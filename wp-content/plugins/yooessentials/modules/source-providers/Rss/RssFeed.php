<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Rss;

/**
 * Adapted from https://github.com/dg/rss-php/blob/master/src/Feed.php
 *
 * @see https://en.wikipedia.org/wiki/RSS
 *
 * @property-read string $title
 * @property-read string $description
 * @property-read string $url
 * @property-read array $item
 */
class RssFeed
{
    public const TYPE_RSS_0_90 = '0.90';
    public const TYPE_RSS_0_91 = '0.91';
    public const TYPE_RSS_0_92 = '0.92';
    public const TYPE_RSS_1_0 = '1.0';
    public const TYPE_RSS_2_0 = '2.0';
    public const TYPE_ATOM = 'Atom';
    public const TYPE_OTHER = 'Other';

    public const DATETIME_TAGS = ['timestamp', 'pubDate', 'lastBuildDate', 'updated'];

    private \SimpleXMLElement $xml;

    private string $type;

    public function __construct(\SimpleXMLElement $xml, string $type = self::TYPE_RSS_2_0)
    {
        $this->xml = $xml;
        $this->type = $type;
    }

    public function author(): string
    {
        return $this->xml->author;
    }

    public function category(): string
    {
        return $this->xml->category;
    }

    public function copyright(): string
    {
        if ($this->type() === self::TYPE_ATOM) {
            return $this->xml->rights;
        }

        return $this->xml->copyright;
    }

    public function subtitle(): string
    {
        if ($this->type() === self::TYPE_ATOM) {
            return $this->xml->subtitle;
        }

        return '';
    }

    // RSS only, with support with ATOM 1.0 corresponding fields
    public function description(): string
    {
        if ($this->type() === self::TYPE_ATOM) {
            return implode(' ', array_filter([$this->xml->summary, $this->xml->content]));
        }

        return $this->xml->description;
    }

    // ATOM 1.0 only
    public function summary(): string
    {
        return $this->xml->summary;
    }

    // ATOM 1.0 only
    public function content(): string
    {
        return $this->xml->content;
    }

    public function generator(): string
    {
        return $this->xml->generator;
    }

    public function id(): string
    {
        if ($this->type() === self::TYPE_ATOM) {
            return $this->xml->id;
        }

        return $this->xml->guid;
    }

    public function image(): array
    {
        $image = $this->xml->image;
        if ($this->type() === self::TYPE_ATOM) {
            $image = $this->xml->logo;
        }

        if (!$image) {
            return [];
        }

        return [
            'url' => $image->url,
            'title' => $image->title,
            'link' => $image->link,
        ];
    }

    // Item / entry
    public function items(): array
    {
        switch ($this->type()) {
            case self::TYPE_ATOM:
                $items = $this->xml->entry;

                break;

            case self::TYPE_OTHER:
                $children = array_keys((array) $this->xml->children());
                $itemKey = array_shift($children);
                $items = $this->xml->$itemKey;

                break;

            default:
                $items = $this->xml->item;

                break;
        }

        $entries = [];
        foreach ($items as $item) {
            $entries[] = $this->toArray($item);
        }

        return $entries;
    }

    public function __get($name)
    {
        return $this->xml->{$name};
    }

    public function __set($name, $value)
    {
        throw new \Exception("Cannot assign to a read-only property '$name'.");
    }

    public function xml(): \SimpleXMLElement
    {
        return $this->xml;
    }

    public function type(): string
    {
        return $this->type;
    }

    /**
     * @param \SimpleXMLElement|null $xml
     * @return array|string
     */
    public function toArray(?\SimpleXMLElement $xml = null)
    {
        if ($xml === null) {
            $xml = $this->xml;
        }

        $data = $this->extractDataFromXml($xml);

        if (count($data) <= 0) {
            return (string) $xml;
        }

        return $this->parseExtractedData($data);
    }

    private function extractDataFromXml(?\SimpleXMLElement $xml): array
    {
        $data = (array) $xml;
        $attributes = (array) $xml->attributes();
        $data = array_merge($data, $attributes['@attributes'] ?? []);
        unset($data['@attributes']);

        foreach ($xml->getNamespaces(true) as $prefix => $ns) {
            if ($prefix === '') {
                continue;
            }

            $attributes = (array) $xml->attributes($ns);
            foreach ($attributes['@attributes'] ?? [] as $name => $value) {
                $data[$prefix . ':' . $name] = $value;
            }

            $children = (array) $xml->children($ns);
            foreach ($children as $name => $value) {
                $data[$prefix . ':' . $name] = $value;
            }
        }

        return $data;
    }

    private function parseExtractedData(array $data): array
    {
        $arr = [];

        foreach ($data as $tag => $childValue) {
            if ($childValue instanceof \SimpleXMLElement) {
                $arr[$tag] = $this->toArray($childValue);

                continue;
            }

            if (is_string($tag) && in_array($tag, self::DATETIME_TAGS)) {
                $arr[$tag] = \DateTime::createFromFormat('U', $childValue);

                continue;
            }

            if (is_array($childValue)) {
                $arr[$tag] = $this->parseExtractedData($childValue);

                continue;
            }

            $arr[$tag] = trim((string) $childValue);
        }

        return $arr;
    }
}
