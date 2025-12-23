<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action;

use ZOOlanders\YOOessentials\HasLocalConfig;

abstract class StandardAction implements Action
{
    use HasLocalConfig;

    public function __construct(array $config = [])
    {
        $this->setConfig($config);
    }

    public function name(): string
    {
        return $this->config('name', '');
    }

    public function title(): string
    {
        return $this->config('title', '');
    }

    public function panel(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): Action
    {
        $this->config = $config;

        return $this;
    }

    public function withConfig(array $config): Action
    {
        $this->config = array_merge_recursive($this->config, $config);

        return $this;
    }

    public function getConfig(): array
    {
        return $this->config();
    }
}
