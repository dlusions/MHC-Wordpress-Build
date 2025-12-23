<?php
/**
 * @package DJ-SectionsAnywhere
 * @copyright Copyright (C) 2017  DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 *
 * DJContentFilters is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJContentFilters is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJContentFilters. If not, see <http://www.gnu.org/licenses/>.
 *
 */


namespace DJExtensions\Yootheme\DJPopup\Transform;


class SectionTransform
{
    /**
     * Transform callback.
     *
     * @param object $node
     * @param array $params
     */
    public function __invoke($node, array $params)
    {
        if ($node->type != 'section') return;
        if(isset($node->props['djpopup']->enabled) && $node->props['djpopup']->enabled && $node->props['status'] != 'disabled') {


            foreach ($node->props['djpopup'] as $key => $value) {
                $node->props['djpopup_' . $key] = $value;
            }

            $node->type = 'djpopup';

        }

    }
}
