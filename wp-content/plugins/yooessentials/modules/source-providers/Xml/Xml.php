<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Xml;

use SimpleXMLElement;
use ZOOlanders\YOOessentials\Source\Provider\Xml\Parsers\ArrayParser;
use ZOOlanders\YOOessentials\Source\Provider\Xml\Parsers\FallbackParser;
use ZOOlanders\YOOessentials\Source\Provider\Xml\Parsers\SimpleXmlParser;
use ZOOlanders\YOOessentials\Source\Provider\Xml\Parsers\XmlParser;

class Xml
{
    protected ?SimpleXMLElement $xml = null;
    protected array $parsers = [];

    public function __construct(?SimpleXMLElement $xml = null)
    {
        $this->xml = $xml;
        $this->parsers = $this->defaultParsers();
    }

    public function addParser(XmlParser $parser): self
    {
        $this->parsers[] = $parser;

        return $this;
    }

    protected function defaultParsers(): array
    {
        return [
            new SimpleXmlParser(),
            new ArrayParser(),
            new FallbackParser(),
        ];
    }

    public function toArray(): array
    {
        $data = $this->decode($this->xml);
        if (!is_array($data)) {
            return [];
        }

        return $data;
    }

    /**
     * @param SimpleXMLElement $xml
     * @return array|string
     */
    public function decode(SimpleXMLElement $xml)
    {
        $data = $this->extractDataFromXml();

        if (count($data) <= 0) {
            return (string) $xml;
        }

        return $this->parseData($data);
    }

    protected function extractDataFromXml(): array
    {
        $data = (array) $this->xml;
        $attributes = (array) $this->xml->attributes();
        $data = array_merge($data, $attributes['@attributes'] ?? []);
        unset($data['@attributes']);

        foreach ($this->xml->getNamespaces(true) as $prefix => $ns) {
            if ($prefix === '') {
                continue;
            }

            $attributes = (array) $this->xml->attributes($ns);
            foreach ($attributes['@attributes'] ?? [] as $name => $value) {
                $data[$prefix . ':' . $name] = $value;
            }

            $children = (array) $this->xml->children($ns);
            foreach ($children as $name => $value) {
                $data[$prefix . ':' . $name] = $value;
            }
        }

        return $data;
    }

    public function parseData(array $data): array
    {
        $arr = [];

        foreach ($data as $tag => $childValue) {
            foreach ($this->parsers as $parser) {
                if ($parser->shouldParse($tag, $childValue)) {
                    $arr[$tag] = $parser->parse($tag, $childValue);

                    break;
                }
            }
        }

        return $arr;
    }
}
