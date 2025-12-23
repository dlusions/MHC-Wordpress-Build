<?php
/**
 * @package   Custom Element for Yootheme Pro
 * @author    JPro Studio https://www.jpro.studio/
 * @copyright Copyright (C) JPro Studio
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
use YOOtheme\Builder;
use YOOtheme\Path;

return [

    'extend' => [

        Builder::class => function (Builder $builder) {
           
            $builder->addTypePath(Path::get('./elements/*/element.json'), null, 1);

        },

    ]

];
