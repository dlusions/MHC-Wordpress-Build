<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use YOOtheme\Path;
use YOOtheme\Builder;

return [

    'extend' => [
        Builder::class => function (Builder $builder, $app) {
            $builder->addTypePath(Path::get('./*/element.json'));
        },
    ],
];
