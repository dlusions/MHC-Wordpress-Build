<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Csv;

use YOOtheme\Event;
use YOOtheme\File;
use YOOtheme\Path;
use YOOtheme\Str;
use ZOOlanders\YOOessentials\Source\Resolver\HasCacheTimes;
use ZOOlanders\YOOessentials\Source\Type\AbstractSourceType;
use ZOOlanders\YOOessentials\Source\Type\DynamicSourceInputType;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;
use ZOOlanders\YOOessentials\Source\Provider\Csv\Type\CsvFilterType;
use ZOOlanders\YOOessentials\Source\Provider\Csv\Type\CsvOrderingType;
use ZOOlanders\YOOessentials\Util\Prop;
use ZOOlanders\YOOessentials\Vendor\League\Csv\Reader;

class CsvSource extends AbstractSourceType implements SourceInterface, HasCacheTimes
{
    private ?Reader $csv = null;

    public function types(): array
    {
        try {
            $this->csv();
        } catch (\Throwable $e) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'provider' => 'csv',
                'error' => $e->getMessage(),
            ]);

            return [];
        }

        $filterType = new CsvFilterType();
        $orderingType = new CsvOrderingType();
        $objectType = new Type\CsvFileType($this);
        $queryType = new Type\CsvRecordsQueryType($this, $objectType);

        return [
            $filterType,
            $orderingType,
            new DynamicSourceInputType($filterType),
            new DynamicSourceInputType($orderingType),
            $objectType,
            $queryType,
        ];
    }

    public function csv(): Reader
    {
        if ($this->csv !== null) {
            return $this->csv;
        }

        $file = $this->config('file');

        if ($file and !Path::isAbsolute($file) and !Str::startsWith($file, '~')) {
            $file = "~/$file";
        }

        if (!File::exists($file)) {
            throw new \Exception("CSV File Not Found at '{$file}'");
        }

        $enclosure = Prop::parseString($this->config(), 'enclosure', '"', 1);
        $delimiter = Prop::parseString($this->config(), 'delimiter', ',', 1);

        $csv = Reader::createFromPath(File::get($file), 'r')
            ->setHeaderOffset(0)
            ->skipEmptyRecords()
            ->setEnclosure($enclosure)
            ->setDelimiter($delimiter);

        $headers = $csv->getHeader();

        if (count(array_filter($headers)) !== count($headers)) {
            throw new \Exception('CSV File contains empty Headers.');
        }

        if (count(array_unique($headers)) !== count($headers)) {
            $diff = array_diff_key($headers, array_unique($headers));

            throw new \Exception('CSV File contains duplicate Headers: ' . implode(', ', $diff));
        }

        return $this->csv = $csv;
    }

    public function defaultCacheTime(): int
    {
        return $this->config('cache_default', HasCacheTimes::DEFAULT_CACHE_TIME);
    }

    public function minCacheTime(): int
    {
        return 0;
    }
}
