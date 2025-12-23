<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Config;

interface ConfigRepositoryInterface
{
    public function authorize(): bool;

    public function load(ConfigInterface $config): void;

    public function save(ConfigInterface $config): void;

    public function export(ConfigInterface $config): array;
}
