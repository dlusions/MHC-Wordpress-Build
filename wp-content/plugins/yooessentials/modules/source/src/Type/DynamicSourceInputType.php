<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\TypeInterface;
use ZOOlanders\YOOessentials\Source\GraphQL\AbstractInputType;

class DynamicSourceInputType extends AbstractInputType
{
    public const TYPE_LABEL = 'Dynamic Source Props';
    public const TYPE_NAME = 'DynamicSourceProps';

    protected TypeInterface $inputType;

    public function __construct(TypeInterface $inputType)
    {
        $this->inputType = $inputType;
    }

    public static function nameForInputType(string $inputTypeName): string
    {
        return self::TYPE_NAME . $inputTypeName;
    }

    public function name(): string
    {
        return static::nameForInputType($this->inputType->name());
    }

    public function label(): string
    {
        return self::TYPE_LABEL . ' - ' . $this->inputType->label();
    }

    public function config(): array
    {
        return [
            'fields' => [
                'id' => [
                    'type' => 'String',
                ],
                'props' => [
                    'type' => $this->inputType->name(),
                ],
                'source' => [
                    'type' => 'String',
                ],
            ],
        ];
    }
}
