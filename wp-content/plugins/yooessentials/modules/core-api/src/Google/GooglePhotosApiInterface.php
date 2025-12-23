<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Google;

interface GooglePhotosApiInterface
{
    public function albums(array $args = []): array;

    public function album(string $albumId): array;

    public function mediaItemsSearch(array $args = []): array;
}
