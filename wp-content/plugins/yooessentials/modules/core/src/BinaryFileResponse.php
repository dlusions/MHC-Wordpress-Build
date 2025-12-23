<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials;

use DateTime;
use YOOtheme\File;
use YOOtheme\Http\Message\Stream;
use YOOtheme\Http\Response;

/**
 * Inspired by Symfony's BinaryFileResponse class, original authors:
 * @author Niklas Fiekas <niklas.fiekas@tu-clausthal.de>
 * @author stealth35 <stealth35-php@live.fr>
 * @author Igor Wiedler <igor@wiedler.ch>
 * @author Jordan Alliot <jordan.alliot@gmail.com>
 * @author Sergey Linnik <linniksa@gmail.com>
 */
class BinaryFileResponse extends Response
{
    public const DISPOSITION_ATTACHMENT = 'attachment';
    public const DISPOSITION_INLINE = 'inline';

    protected $file;

    public function withFile(
        $resource,
        $mimetype = null,
        string $contentDisposition = self::DISPOSITION_ATTACHMENT,
        bool $lastModified = true,
        bool $autoEtag = true
    ): self {
        if ($mimetype === null) {
            $mimetype = Util\File::getMimeType($resource);
        }

        $response = parent::withFile($resource, $mimetype)
            ->setFile($resource)
            ->setContentDisposition($contentDisposition);

        if ($autoEtag) {
            $response = $response->setAutoEtag();
        }

        if ($lastModified) {
            $response = $response->setLastModified(new DateTime());
        }

        return $response;
    }

    public function withFileContents(
        string $contents,
        $mimetype = null,
        string $contentDisposition = self::DISPOSITION_ATTACHMENT,
        bool $lastModified = true,
        bool $autoEtag = true
    ): self {
        $body = Stream::create($contents);

        $response = $this->withBody($body)
            ->withHeader('Content-Length', $body->getSize())
            ->setContentDisposition($contentDisposition);

        if (!is_null($mimetype)) {
            $response = $response->withHeader('Content-Type', $mimetype);
        }

        if ($autoEtag) {
            $response = $this->setEtag(base64_encode(hash('sha256', $contents, true)));
        }

        if ($lastModified) {
            $response = $response->setAutoLastModified();
        }

        return $response;
    }

    public function setFile(string $path): self
    {
        $this->file = $path;

        return $this;
    }

    /**
     * Marks the response as "public".
     *
     * It makes the response eligible for serving other clients.
     *
     * @return $this
     *
     * @final
     */
    public function setPublic(): object
    {
        return $this->withHeader('Cache-Control', 'public');
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setAutoLastModified(): self
    {
        if (!$this->getFile()) {
            return $this;
        }

        return $this->setLastModified(DateTime::createFromFormat('U', File::getMTime($this->getFile())));
    }

    /**
     * Sets the Last-Modified HTTP header with a DateTime instance.
     *
     * Passing null as value will remove the header.
     *
     * @return $this
     *
     * @final
     */
    public function setLastModified(?\DateTimeInterface $date = null): object
    {
        if (null === $date) {
            return $this->withoutHeader('Last-Modified');
        }

        if ($date instanceof DateTime) {
            $date = \DateTimeImmutable::createFromMutable($date);
        }

        /** @var \DateTimeImmutable $date */
        $date = $date->setTimezone(new \DateTimeZone('UTC'));

        return $this->withHeader('Last-Modified', $date->format('D, d M Y H:i:s') . ' GMT');
    }

    public function setAutoEtag(): self
    {
        return $this->setEtag(base64_encode(hash_file('sha256', $this->file, true)));
    }

    /**
     * Sets the ETag value.
     *
     * @param string|null $etag The ETag unique identifier or null to remove the header
     * @param bool $weak Whether you want a weak ETag or not
     *
     * @return $this
     *
     * @final
     */
    public function setEtag(?string $etag = null, bool $weak = false): self
    {
        if (null === $etag) {
            return $this->withoutHeader('Etag');
        }
        if (0 !== strpos($etag, '"')) {
            $etag = '"' . $etag . '"';
        }

        return $this->withHeader('ETag', (true === $weak ? 'W/' : '') . $etag);
    }

    public function setContentDisposition(
        string $disposition = self::DISPOSITION_INLINE,
        string $filename = '',
        string $filenameFallback = ''
    ): self {
        if ('' === $filename) {
            $filename = basename($this->file);
        }

        if ('' === $filenameFallback && (!preg_match('/^[\x20-\x7e]*$/', $filename) || false !== strpos($filename, '%'))) {
            $encoding = mb_detect_encoding($filename, null, true) ?: '8bit';

            for ($i = 0, $filenameLength = mb_strlen($filename, $encoding); $i < $filenameLength; ++$i) {
                $char = mb_substr($filename, $i, 1, $encoding);

                if ('%' === $char || \ord($char) < 32 || \ord($char) > 126) {
                    $filenameFallback .= '_';
                } else {
                    $filenameFallback .= $char;
                }
            }
        }

        $dispositionHeader = self::makeDisposition($disposition, $filename, $filenameFallback);

        return $this->withHeader('Content-Disposition', $dispositionHeader);
    }

    public function getEtag(): ?string
    {
        return $this->getHeaderLine('ETag');
    }

    public function getLastModified(): ?\DateTimeInterface
    {
        $date = $this->getHeaderLine('Last-Modified');
        $date = DateTime::createFromFormat(\DATE_RFC2822, $date);

        if ($date === false) {
            return null;
        }

        return $date;
    }

    /**
     * Generates an HTTP Content-Disposition field-value.
     *
     * @param string $disposition One of "inline" or "attachment"
     * @param string $filename A unicode string
     * @param string $filenameFallback A string containing only ASCII characters that
     *                                 is semantically equivalent to $filename. If the filename is already ASCII,
     *                                 it can be omitted, or just copied from $filename
     *
     * @return string A string suitable for use as a Content-Disposition field-value
     *
     * @throws \InvalidArgumentException
     *
     * @see RFC 6266
     */
    public static function makeDisposition(string $disposition, string $filename, string $filenameFallback = ''): string
    {
        if (!\in_array($disposition, [self::DISPOSITION_ATTACHMENT, self::DISPOSITION_INLINE])) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The disposition must be either "%s" or "%s".',
                    self::DISPOSITION_ATTACHMENT,
                    self::DISPOSITION_INLINE
                )
            );
        }

        if ('' === $filenameFallback) {
            $filenameFallback = $filename;
        }

        // filenameFallback is not ASCII.
        if (!preg_match('/^[\x20-\x7e]*$/', $filenameFallback)) {
            throw new \InvalidArgumentException('The filename fallback must only contain ASCII characters.');
        }

        // percent characters aren't safe in fallback.
        if (false !== strpos($filenameFallback, '%')) {
            throw new \InvalidArgumentException('The filename fallback cannot contain the "%" character.');
        }

        // path separators aren't allowed in either.
        if (
            false !== strpos($filename, '/') ||
            false !== strpos($filename, '\\') ||
            false !== strpos($filenameFallback, '/') ||
            false !== strpos($filenameFallback, '\\')
        ) {
            throw new \InvalidArgumentException('The filename and the fallback cannot contain the "/" and "\\" characters.');
        }

        $params = ['filename' => $filenameFallback];
        if ($filename !== $filenameFallback) {
            $params['filename*'] = "utf-8''" . rawurlencode($filename);
        }

        return $disposition . '; ' . self::toParamsString($params, ';');
    }

    public static function toParamsString(array $assoc, string $separator): string
    {
        $parts = [];
        foreach ($assoc as $name => $value) {
            if (true === $value) {
                $parts[] = $name;
            } else {
                $parts[] = $name . '=' . self::quoteParam($value);
            }
        }

        return implode($separator . ' ', $parts);
    }

    public static function quoteParam(string $s): string
    {
        if (preg_match('/^[a-z0-9!#$%&\'*.^_`|~-]+$/i', $s)) {
            return $s;
        }

        return '"' . addcslashes($s, '"\\"') . '"';
    }
}
