<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver\Filters;

abstract class DatabaseOperators
{
    public const EQUAL = '=';
    public const NOT_EQUAL = '!=';
    public const LESS = '<';
    public const GREATER = '>';
    public const LESS_OR_EQUAL = '<=';
    public const GREATER_OR_EQUAL = '>=';
    public const CONTAINS = '%';
    public const STARTS_WITH = 'LIKE %';
    public const STARTS_NOT_WITH = 'NOT LIKE %';
    public const ENDS_WITH = '% LIKE';
    public const ENDS_NOT_WITH = '% NOT LIKE';
    public const NULL = 'null';
    public const NOT_NULL = '!null';
    public const LIKE = 'LIKE';
}
