<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Rss;

use SimpleXMLElement;
use ZOOlanders\YOOessentials\Auth\Auth;
use ZOOlanders\YOOessentials\Source\Provider\Xml\XmlService;

class RssService
{
    private XmlService $xmlService;
    private int $cacheTime = 0;
    private ?string $cacheKey = null;

    public function __construct(XmlService $client)
    {
        $this->xmlService = $client;
    }

    public function withCache(int $time, ?string $cacheKey = null): self
    {
        $this->cacheTime = $time;
        $this->cacheKey = $cacheKey;

        return $this;
    }

    public function load(string $url, ?Auth $auth = null): RssFeed
    {
        $xml = $this->xmlService
            ->withCache($this->cacheTime, $this->cacheKey)
            ->loadFromUrl($url, $auth);

        if ($xml->channel) {
            return $this->fromRss($xml);
        }

        if (
            in_array('http://www.w3.org/2005/Atom', $xml->getDocNamespaces(), true) ||
            in_array('http://purl.org/atom/ns#', $xml->getDocNamespaces(), true)
        ) {
            return $this->fromAtom($xml);
        }

        return $this->fromOtherXml($xml);
    }

    public function loadRss(string $url, ?Auth $auth = null): RssFeed
    {
        return $this->fromRss($this->xmlService->loadFromUrl($url, $auth));
    }

    public function loadAtom(string $url, ?Auth $auth = null): RssFeed
    {
        return $this->fromAtom($this->xmlService->loadFromUrl($url, $auth));
    }

    private function fromRss(SimpleXMLElement $xml): RssFeed
    {
        if (!$xml->channel) {
            throw new \Exception('Invalid feed.');
        }

        self::adjustNamespaces($xml);

        $this->fixRssTimeStamp($xml->channel, false);

        foreach ($xml->channel->item as $item) {

            // generate 'url' & 'timestamp' tags
            $item->url = (string) $item->link;

            $this->fixRssTimeStamp($item);
        }

        return new RssFeed($xml->channel, $xml['version']);
    }

    private function fromAtom(SimpleXMLElement $xml): RssFeed
    {
        // generate 'url' & 'timestamp' tags
        foreach ($xml->entry as $entry) {
            $entry->url = (string) $entry->link['href'];
            $entry->timestamp = strtotime($entry->updated);
        }

        return new RssFeed($xml, RssFeed::TYPE_ATOM);
    }

    private function fromOtherXml(SimpleXMLElement $xml): RssFeed
    {
        return new RssFeed($xml, RssFeed::TYPE_OTHER);
    }

    public static function adjustNamespaces(SimpleXMLElement $el): void
    {
        foreach ($el->getNamespaces(true) as $prefix => $ns) {
            if ($prefix === '') {
                continue;
            }
            $children = (array) $el->children($ns);
            foreach ($children as $tag => $content) {
                $el->addChild($prefix . '_' . $tag, $content);

                if ($prefix === 'itunes' && $tag === 'image') {
                    $test = $el->{$prefix . '_' . $tag};
                    dd($test->attributes()->href, $content->attributes()->href);
                }
            }
        }
    }

    private function fixRssTimeStamp(SimpleXMLElement $item, bool $forceTag = true): void
    {
        $attributes = [
            'dc:date' => 'timestamp',
            'pubDate' => 'timestamp',
            'lastBuildDate' => 'lastBuildDate',
        ];

        foreach ($attributes as $attribute => $tag) {
            $tag = $forceTag ? $tag : $attribute;
            if (isset($item->{$attribute})) {
                $item->{$tag} = strtotime($item->{$attribute});

                if ($tag !== $attribute) {
                    unset($item->{$attribute});
                }
            }
        }
    }
}
