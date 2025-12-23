<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\LinkedIn;

interface LinkedInApiInterface
{
    public function post(string $urn): array;

    public function posts(array $args = []): array;

    public function postsByAuthor(string $urn, array $args = []): array;

    public function socialActions(string $urn, array $args = []): array;

    public function organization(string $urn, array $args = []): array;

    public function organizations(): array;

    public function image(string $urn): array;

    public function video(string $urn): array;

    public function article(string $urn): array;

    public function people(string $id): array;
}
