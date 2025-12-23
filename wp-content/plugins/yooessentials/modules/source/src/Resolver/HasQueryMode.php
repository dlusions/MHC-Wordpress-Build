<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Resolver;

trait HasQueryMode
{
    protected $mode = QueryMode::MODE_AND;

    public function mode(string $mode = QueryMode::MODE_AND): self
    {
        $this->mode = $mode;

        return $this;
    }
}
