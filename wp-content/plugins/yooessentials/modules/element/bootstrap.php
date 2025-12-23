<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use YOOtheme\Builder;
use YOOtheme\Path;
use ZOOlanders\YOOessentials\Migration;

return [
    'extend' => [
        Builder::class => function (Builder $builder) {
            $builder->addTypePath(Path::get('./elements/*/element.json'));
        },
    ],

    'yooessentials-migrations' => [
        Migration\Migration110\MigrateSocialSharingElement::class,
        Migration\Migration120\MigrateSocialSharingElement::class,
        Migration\Migration200\MigrateChartElement::class,
        Migration\Migration220\MigrateSocialSharingElement::class,
        Migration\Migration230\MigrateSocialSharingElement::class,
        Migration\Migration230\MigrateSocialSharingItemElement::class,
        Migration\Migration230\RenameSocialSharingItemElementProps::class,
    ]
];
