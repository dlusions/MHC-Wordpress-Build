<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Xml;

use function YOOtheme\app;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Util;
use ZOOlanders\YOOessentials\Source\Resolver\HasCacheTimes;
use ZOOlanders\YOOessentials\Source\Type\AbstractSourceType;
use ZOOlanders\YOOessentials\Source\Type\DynamicSourceInputType;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Vendor\Symfony\Component\Cache\Adapter\FilesystemAdapter;
use ZOOlanders\YOOessentials\Vendor\Symfony\Contracts\Cache\CacheInterface;
use ZOOlanders\YOOessentials\Vendor\Symfony\Contracts\Cache\ItemInterface;

class XmlSourceFile extends AbstractSourceType implements SourceInterface, HasCacheTimes
{
    use CachesResolvedSourceData;

    public const NAME = 'xml-file';

    protected string $configFile = 'config-file.json';

    protected ?Xml $xml = null;

    /**
     * @param array $config
     * @return XmlSourceUrl|XmlSourceFile
     */
    public static function fromConfig(array $config)
    {
        $type = $config['provider'] ?? self::NAME;
        if ($type === XmlSourceFile::NAME) {
            return new XmlSourceFile($config);
        }

        return new XmlSourceUrl($config);
    }

    public function objectType(): Type\XmlType
    {
        $this->xml();

        return new Type\XmlType($this);
    }

    public function types(): array
    {
        try {
            $this->xml();
        } catch (\Throwable $e) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'provider' => 'xml',
                'error' => $e->getMessage(),
            ]);

            return [];
        }

        $objectType = $this->objectType();
        $queryType = new Type\XmlQueryType($this, $objectType);
        $filterType = new Type\XmlFilterType();
        $orderingType = new Type\XmlOrderingType();

        return array_merge(
            [
                $filterType,
                $orderingType,
                new DynamicSourceInputType($filterType),
                new DynamicSourceInputType($orderingType),
                new Type\XmlImageType(),
            ],
            $objectType->types(),
            [$objectType, $queryType]
        );
    }

    protected function loadXml(bool $fresh = false): \SimpleXMLElement
    {
        /** @var XmlService $xmlService */
        $xmlService = app(XmlService::class);

        $file = $this->config('file');

        return $xmlService->loadFromFile($file);
    }

    public function simpleXml(bool $fresh = false): \SimpleXMLElement
    {
        $xml = $this->loadXml($fresh);

        $xpath = $this->config('xpath');
        if (!$xpath) {
            return $xml;
        }

        $filteredXml = $xml->xpath($xpath);

        if (!$filteredXml) {
            return $xml;
        }

        if (!is_array($filteredXml)) {
            return $xml;
        }

        if (count($filteredXml) < 1) {
            return $xml;
        }

        if (count($filteredXml) < 2) {
            return array_shift($filteredXml);
        }

        // add the XML
        $document = new \DOMDocument('1.0');

        $node = $document->importNode(dom_import_simplexml(new \SimpleXMLElement('<root></root>')));
        $root = $document->appendChild($node);

        foreach ($filteredXml as $xmlNode) {
            $node = dom_import_simplexml($xmlNode);
            $node = $document->importNode($node, true);
            $root->appendChild($node);
        }

        return new \SimpleXMLElement($document->saveXML());
    }

    public function xml(): Xml
    {
        if ($this->xml !== null) {
            return $this->xml;
        }

        // TODO: move to CachesResolvedSourceData trait
        $cacheKey = self::getCacheKey() . sha1(json_encode($this->config()));
        $cacheTime = self::getCacheTime($this, []);

        /** @var FilesystemAdapter $cache */
        $cache = app(CacheInterface::class);

        if ($cacheTime <= 0) {
            $cache->delete($cacheKey);
        }

        // This is needed for cases when the xml becomes empty or unreachable when refreshing cache.
        // We store a "long lived" cache that is used if and when the new version in empty as a fallback
        $longLivedCacheKey = $cacheKey . '-fallback';

        $simpleXml = $cache->get($cacheKey, function (ItemInterface $item) use ($longLivedCacheKey, $cacheTime, $cacheKey, $cache) {
            $item->expiresAfter($cacheTime);

            $xml = $this->simpleXml()->asXML();
            $xmlContent = (new Xml($this->simpleXml()))->toArray();

            /** @var ItemInterface $longLivedCache */
            $longLivedCache = $cache->getItem($longLivedCacheKey);

            // try forcefully refreshing it if it's empty
            if (empty($xmlContent)) {
                $xml = $this->simpleXml(true);
                $xmlContent = (new Xml($this->simpleXml()))->toArray();
                $xml = $xml->asXML();
            }

            // Empty new content, and we have a previously persisted version
            if (empty($xmlContent) && $longLivedCache->isHit()) {
                return $longLivedCache->get();
            }

            // If the new content is not empty, persist it also in the long lived cache
            if (!empty($xmlContent)) {
                $longLivedCache->set($xml);
                $cache->save($longLivedCache);
            }

            return $xml;
        });

        return $this->xml = new Xml(new \SimpleXMLElement($simpleXml));
    }

    public function dataTypes(): array
    {
        $dataTypes = [];

        foreach (array_filter($this->config('data_types', [])) as $config) {
            $options = Util\Arr::omit($config, ['sourceType', 'destinationType']);
            $type = $config['sourceType'] ?? null;

            if (!$type) {
                continue;
            }

            $dataTypes[$type] = [
                'type' => $config['destinationType'],
                'options' => $options
            ];
        }

        return $dataTypes;
    }

    public function defaultCacheTime(): int
    {
        return $this->config('cache_default', HasCacheTimes::DEFAULT_CACHE_TIME);
    }

    public function minCacheTime(): int
    {
        return 0;
    }

    public static function getCacheKey(): string
    {
        return 'xml-source';
    }
}
