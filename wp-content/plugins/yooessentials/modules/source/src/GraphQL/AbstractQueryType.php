<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\GraphQL;

use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

abstract class AbstractQueryType extends GenericType implements HasSourceInterface
{
    use HasSource;

    public const DESCRIPTION = '';

    public function __construct(SourceInterface $source)
    {
        $this->source = $source;
    }

    public function type(): string
    {
        return TypeInterface::TYPE_QUERY;
    }

    public function description(): string
    {
        return static::DESCRIPTION;
    }
}
