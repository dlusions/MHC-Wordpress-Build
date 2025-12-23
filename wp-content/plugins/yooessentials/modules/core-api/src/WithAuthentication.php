<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api;

trait WithAuthentication
{
    protected string $apiKey = '';

    protected string $accessToken = '';

    public function withApiKey(string $key): self
    {
        $this->apiKey = $key;
        $this->accessToken = '';

        return $this;
    }

    public function withAccessToken(string $token): self
    {
        $this->apiKey = '';
        $this->accessToken = $token;

        return $this;
    }

    protected function getAuthorizationHeader(): array
    {
        if ($this->accessToken) {
            return ['Authorization' => 'Bearer ' . $this->accessToken];
        }

        return [];
    }
}
