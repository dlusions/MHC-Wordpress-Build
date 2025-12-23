<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Validation;

use SplFileInfo;
use YOOtheme\Http\Message\UploadedFile;
use ZOOlanders\YOOessentials\Vendor\Respect\Validation\Exceptions\ComponentException;
use ZOOlanders\YOOessentials\Vendor\Respect\Validation\Rules\AbstractRule;
use ZOOlanders\YOOessentials\Util\File;

class Size extends AbstractRule
{
    /**
     * @var string|int|null
     */
    private $minSize;

    private ?float $minValue;

    /**
     * @var string|int|null
     */
    private $maxSize;

    private ?float $maxValue;

    /**
     * @param string|int|null $minSize
     * @param string|int|null $maxSize
     */
    public function __construct($minSize = null, $maxSize = null)
    {
        $this->minSize = $minSize;
        $this->minValue = $minSize ? $this->toBytes($minSize) : null;
        $this->maxSize = $maxSize;
        $this->maxValue = $maxSize ? $this->toBytes($maxSize) : null;
    }

    /**
     * {@inheritDoc}
     */
    public function validate($input): bool
    {
        if ($input instanceof UploadedFile) {
            return $this->isValidSize($input->getSize());
        }

        if ($input instanceof SplFileInfo) {
            return $this->isValidSize($input->getSize());
        }

        if (is_string($input)) {
            return $this->isValidSize((int) filesize($input));
        }

        return false;
    }

    /**
     * @param mixed $size
     */
    private function toBytes($size): float
    {
        $value = File::toBytes($size);

        if (!is_numeric($value)) {
            throw new ComponentException(sprintf('"%s" is not a recognized file size.', (string) $size));
        }

        return $value;
    }

    private function isValidSize(float $size): bool
    {
        if ($this->minValue !== null && $this->maxValue !== null) {
            return $size >= $this->minValue && $size <= $this->maxValue;
        }

        if ($this->minValue !== null) {
            return $size >= $this->minValue;
        }

        return $size <= $this->maxValue;
    }
}
