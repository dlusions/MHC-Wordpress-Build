<?php
/**
 * @package DJPopup
 * @copyright Copyright (C) 2017  DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 *
 * DJPopup is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJPopup is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJPopup. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace YOOtheme;


// Initialize Joomla framework
use DJExtensions\Yootheme\DJPopup\Listener\BuilderListener;
use DJExtensions\Yootheme\DJPopup\Transform\SectionTransform;
use YOOtheme\Str;

return [
    'events' => [
        'customizer.init' => [
            BuilderListener::class => ['initCustomizer', -9]
        ],
        'builder.type' => [
            BuilderListener::class => 'addFormPanel'
        ],
        'theme.head' => [
            BuilderListener::class => ['initHead',-10],
        ],
    ],
    'extend' => [
        Builder::class => function (Builder $builder, $app) {
            $builder->addTypePath(Path::get('./elements/*/element.json'));
            $builder->addTypePath(Path::get('./triggers/*/element.json'));
            $builder->addTransform('prerender', new SectionTransform);
        }

    ]

];
