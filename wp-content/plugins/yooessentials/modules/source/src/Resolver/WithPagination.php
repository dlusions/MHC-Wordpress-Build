<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver;

interface WithPagination
{
    public const DEFAULT_OFFSET = 0;
    public const DEFAULT_LIMIT = 20;

    public function limit(int $limit): self;

    public function offset(int $offset): self;
}
