<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Vimeo;

interface VimeoApiInterface
{
    public function videos(array $args = []): array;

    public function userVideos(string $userId, array $args = []): array;

    public function myVideos(array $args = []): array;

    public function myShowcaseVideos(string $showcaseId, array $args = []): array;

    public function myFolderVideos(string $folderId, array $args = []): array;
}
