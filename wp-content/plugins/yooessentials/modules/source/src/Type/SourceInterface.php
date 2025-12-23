<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\TypeInterface;

interface SourceInterface
{
    public function bind(array $config): SourceInterface;

    public function id(): string;

    public function name(): string;

    public function description(): string;

    public function queryName(): string;

    public function queryType(): MainQueryType;

    /** @return array|TypeInterface[] */
    public function types(): array;

    public function config(?string $key = null, $default = null);

    public function metadata(): object;
}
