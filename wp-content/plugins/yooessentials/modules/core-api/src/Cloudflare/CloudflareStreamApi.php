<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Cloudflare;

// https://developers.cloudflare.com/api/resources/stream/
class CloudflareStreamApi extends CloudflareApi
{
    public function withAccount(string $account): self
    {
        $this->apiEndpoint = "{$this->apiEndpoint}/accounts/$account";

        return $this;
    }

    public function embed(string $uid): string
    {
        return $this->get("stream/$uid/embed");
    }

    public function stream(string $uid): array
    {
        return $this->get("stream/$uid");
    }

    public function streams(array $args = []): array
    {
        return $this->get('stream', $args);
    }

    public function updateStream(array $args): array
    {
        return $this->put('stream', $args);
    }

    public function deleteStream(string $uid): array
    {
        return $this->delete("stream/$uid");
    }

    public function createStreamKey()
    {
        return $this->post('stream/keys');
    }

    public function deleteStreamKey(string $id)
    {
        return $this->delete("stream/keys/$id");
    }
}
