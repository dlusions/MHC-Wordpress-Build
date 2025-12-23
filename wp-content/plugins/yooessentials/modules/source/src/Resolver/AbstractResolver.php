<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver;

use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

abstract class AbstractResolver implements SourceResolver, WithPagination
{
    use HasOffsetAndLimit, HasFilterAndOrderConditions;

    protected SourceInterface $source;

    public function __construct(SourceInterface $source, array $args = [], array $root = [])
    {
        $this->source = $source;

        $this->fromArgs($args, $root);
    }

    public function source(): SourceInterface
    {
        return $this->source;
    }
}
