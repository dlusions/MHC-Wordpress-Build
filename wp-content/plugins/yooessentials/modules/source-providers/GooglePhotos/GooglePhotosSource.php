<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\GooglePhotos;

use function YOOtheme\app;
use YOOtheme\Event;
use ZOOlanders\YOOessentials\Api\Google\GooglePhotosApiInterface;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Auth\AuthOAuth;
use ZOOlanders\YOOessentials\Source\Type\AbstractSourceType;

class GooglePhotosSource extends AbstractSourceType
{
    public const PAGE_SIZE_DEFAULT = 25;

    private ?GooglePhotosApiInterface $api = null;

    public function types(): array
    {
        return [
            new Type\GooglePhotosMediaType(),
            new Type\GooglePhotosAlbumType(),
            new Type\GooglePhotosMediaMetadataType(),
            new Type\GooglePhotosAlbumQueryType($this),
            new Type\GooglePhotosAlbumsQueryType($this),
            new Type\GooglePhotosAlbumMediaQueryType($this),
        ];
    }

    public function auth(): ?AuthOAuth
    {
        $account = $this->config()['account'] ?? null;

        if (!$account) {
            throw new \Exception('Auth Account must be specified.');
        }

        return app(AuthManager::class)->auth($account);
    }

    public function api(): GooglePhotosApiInterface
    {
        if ($this->api) {
            return $this->api;
        }

        $this->api = app(GooglePhotosApiInterface::class);

        try {
            $this->api->withAccessToken($this->auth()->accessToken());
        } catch (\Throwable $e) {
            Event::emit('yooessentials.error', [
                'addon' => 'source',
                'provider' => 'google-photos',
                'error' => $e->getMessage(),
            ]);
        }

        return $this->api;
    }
}
