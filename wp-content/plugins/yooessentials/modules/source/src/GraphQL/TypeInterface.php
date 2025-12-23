<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\GraphQL;

interface TypeInterface
{
    public const TYPE_QUERY = 'query';
    public const TYPE_OBJECT = 'object';
    public const TYPE_INPUT = 'input';

    public function type(): string;

    public function name(): string;

    public function label(): string;

    public function config(): array;
}
