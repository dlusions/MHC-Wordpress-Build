<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Util;

use function YOOtheme\app;
use YOOtheme\Path;
use YOOtheme\File as YooFileUtil;
use ZOOlanders\YOOessentials\HttpClientInterface;
use ZOOlanders\YOOessentials\Vendor\Symfony\Component\Mime\MimeTypes;

abstract class File
{
    public static function getMimeType(string $path): ?string
    {
        $mimeTypes = new MimeTypes();
        $mimeType = $mimeTypes->guessMimeType($path);
        $extensionMimeTypes = $mimeTypes->getMimeTypes(YooFileUtil::getExtension($path));

        // If we have a better match using the guessed mime type, but it's still a valid one
        if ($mimeType !== null && in_array($mimeType, $extensionMimeTypes)) {
            return $mimeType;
        }

        // We can't guess, let YTP guess
        if (count($extensionMimeTypes) <= 0) {
            return YooFileUtil::getMimetype($path);
        }

        // based on file estension
        return array_shift($extensionMimeTypes);
    }

    /**
     * @param mixed $size
     */
    public static function toBytes($size): float
    {
        $value = $size;
        $units = ['b', 'kb', 'mb', 'gb', 'tb', 'pb', 'eb', 'zb', 'yb'];

        foreach ($units as $exponent => $unit) {
            if (!preg_match('/^(\d+(.\d+)?)' . $unit . '$/i', (string) $size, $matches)) {
                continue;
            }
            $value = $matches[1] * 1024 ** $exponent;

            break;
        }

        return (float) $value;
    }

    /**
     * Get the next available file name under a certain path
     */
    public static function getUniqueFilepath(string $path): string
    {
        $count = 0;

        while (YooFileUtil::exists($path)) {
            if (($p = '/_(\d+)(\.[^\/]+)$/') and preg_match($p, $path, $matches)) {
                $count = (int) $matches[1];
                $path = preg_replace($p, '\2', $path);
            }

            $count++;
            $path = preg_replace('/([^\/]+)(\.[^\/]+)$/', '\1_' . $count . '\2', $path);
        }

        return $path;
    }

    /**
     * Download and cache locally an image from url
     */
    public static function cacheMedia(string $url, ?string $cacheKey = null, $mimeType = null): string
    {
        if (empty($url)) {
            return $url;
        }

        $cacheKey = $cacheKey ?: 'media-' . sha1($url);
        $rootDir = app()->config->get('app.rootDir');
        $cacheDir = app()->config->get('image.cacheDir') . '/yooessentials';
        $extension = Path::extname(parse_url($url, PHP_URL_PATH)) ?? '';

        if (!$extension && $mimeType) {
            $extension = '.' . self::guessExtension($mimeType);
        }

        $filename = "{$cacheDir}/{$cacheKey}$extension";

        // if no extension try to glob first file as the extension
        // will be extracted from the file mime type when downloaded
        if (!$extension) {
            $filename = YooFileUtil::glob($filename . '{.*}')[0] ?? $filename;
        }

        if (!YooFileUtil::exists($filename)) {
            $client = app(HttpClientInterface::class);

            if (!YooFileUtil::makeDir($cacheDir, 0777, true)) {
                throw new \Exception('Failed to create temp folder.');
            }

            $result = $client->get($url);

            try {
                YooFileUtil::putContents($filename, (string) $result->getBody());
            } catch (\Throwable $e) {
                // Some connection exception happened (Ig unavailable, local img, whatever)
                return $url;
            }

            if (!$extension) {
                $extension = self::guessExtension(self::getMimeType($filename));
                $newFilename = $filename . '.' . $extension;
                YooFileUtil::rename($filename, $newFilename);
                $filename = $newFilename;
            }
        }

        return Path::relative($rootDir, $filename);
    }

    /**
     * Format bytes to kb, mb, gb, tb
     */
    public static function formatBytes(int $size, int $precision = 2): string
    {
        if ($size <= 0) {
            return $size;
        }

        $size = (int) $size;
        $base = log($size) / log(1024);
        $suffixes = [' bytes', ' KB', ' MB', ' GB', ' TB'];

        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    }

    private static function guessExtension(string $mimeType): string
    {
        $mimeTypes = new MimeTypes();
        $extensions = $mimeTypes->getExtensions($mimeType);

        return array_shift($extensions) ?? '';
    }
}
