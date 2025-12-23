<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Xml\Parsers;

class DateTimeParser implements XmlParser
{
    private array $tags = [];
    private string $format = 'U';

    public function __construct(array $tags, string $format = 'U')
    {
        $this->tags = $tags;
        $this->format = $format;
    }

    public function shouldParse(string $tag, $value): bool
    {
        return in_array($tag, $this->tags);
    }

    public function parse(string $tag, $value)
    {
        return \DateTime::createFromFormat($this->format, $value);
    }
}
