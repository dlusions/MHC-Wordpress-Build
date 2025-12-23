<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Csv;

use YOOtheme\File;
use YOOtheme\Path;
use YOOtheme\Str;
use ZOOlanders\YOOessentials\Util;
use ZOOlanders\YOOessentials\Vendor\League\Csv\Reader;
use ZOOlanders\YOOessentials\Vendor\League\Csv\Writer;

trait InteractsWithCsv
{
    private static function getReaderInstance(object $config): Reader
    {
        $csv = Reader::createFromPath(self::resolveCsvPath($config), 'r');
        $csv->setEscape(''); //required in PHP8.4+ to avoid deprecation notices
        $csv->setDelimiter(self::delimeter($config));
        $csv->setEnclosure(self::enclosure($config));

        return $csv;
    }

    private static function getWriterInstance(object $config): Writer
    {
        $csv = Writer::createFromPath(self::resolveCsvPath($config), 'a+');
        $csv->setEscape(''); //required in PHP8.4+ to avoid deprecation notices
        $csv->setDelimiter(self::delimeter($config));
        $csv->setEnclosure(self::enclosure($config));

        self::enforceEndOfLine($csv);

        return $csv;
    }

    private function copyCsvWithoutContent(Reader $csv, string $output, object $config): Writer
    {
        if (!file_exists($output)) {
            touch($output);
        }

        $csv->setHeaderOffset(0);

        $header = self::trimData($csv->getHeader());

        $output = Writer::createFromPath($output, 'w+');
        $output->setEscape(''); //required in PHP8.4+ to avoid deprecation notices
        $output->setDelimiter(self::delimeter($config));
        $output->setEnclosure(self::enclosure($config));

        $output->insertOne($header);

        return $output;
    }

    private static function trimData(array $data): array
    {
        return array_map(fn ($value) => trim($value), $data);
    }

    private static function getCsvHeader(object $config)
    {
        $csv = self::getReaderInstance($config);

        $header = $csv->fetchOne();

        if (!$header) {
            throw new \RuntimeException('No columns found in CSV. At least one column has to be set in the header.');
        }

        // Validate and trim the header cells
        $header = array_map(
            function (string $headerCell, int $index) {
                $headerCell = trim($headerCell);

                if (strlen($headerCell) === 0) {
                    throw new \RuntimeException(
                        sprintf('CSV column at position %s is missing a header. All columns must have a header.', ++$index)
                    );
                }

                return $headerCell;
            },
            $header,
            array_keys($header)
        );

        return $header;
    }

    private static function delimeter(object $config): string
    {
        return Util\Prop::parseString($config, 'separator', ',', 1);
    }

    private static function enclosure(object $config): string
    {
        return Util\Prop::parseString($config, 'enclosure', '"', 1);
    }

    private static function enforceEndOfLine(Writer $csv): void
    {
        $csvContent = $csv->toString();

        if (substr($csvContent, -1) !== "\n" && substr($csvContent, -1) !== "\r") {
            $csv->insertOne([]);
        }
    }

    private static function resolveCsvPath(object $config): string
    {
        $file = $config->file ?? null;

        if (!$file) {
            throw new \RuntimeException('A path to a CSV file must be specified.');
        }

        if (!Str::startsWith($file, '~') && !Str::startsWith($file, '/')) {
            $file = "~/$file";
        }

        if (!File::exists($file) || !File::isFile($file)) {
            throw new \RuntimeException('The specified path is not a valid CSV file.');
        }

        return Path::resolve($file);
    }
}
