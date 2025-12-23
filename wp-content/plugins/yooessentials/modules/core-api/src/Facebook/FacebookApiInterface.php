<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Facebook;

interface FacebookApiInterface
{
    public function page(string $pageId): array;

    public function pages(string $userId): array;

    public function posts(string $userOrPageId, array $filters = []): array;

    public function photos(string $userOrPageId, array $filters = []): array;

    public function events(string $userOrPageId, array $filters = []): array;

    public function reviews(string $userOrPageId, array $filters = []): array;
}
