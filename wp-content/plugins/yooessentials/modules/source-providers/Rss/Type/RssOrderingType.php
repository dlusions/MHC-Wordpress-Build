<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Rss\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\TypeInterface;
use ZOOlanders\YOOessentials\Source\Type\InMemoryOrderingType;

class RssOrderingType extends InMemoryOrderingType implements TypeInterface
{
    public const NAME = 'RssOrdering';
    public const LABEL = 'Ordering';

    public function name(): string
    {
        return self::NAME;
    }

    public function label(): string
    {
        return self::LABEL;
    }
}
