<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Wordpress;

use ZOOlanders\YOOessentials\Translator as TranslatorInterface;

class Translator implements TranslatorInterface
{
    public function translate(string $text): string
    {
        return __($text);
    }
}
