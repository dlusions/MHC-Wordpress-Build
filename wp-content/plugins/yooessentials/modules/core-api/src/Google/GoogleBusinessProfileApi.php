<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Google;

// https://developers.google.com/my-business
// https://developers.google.com/my-business/reference/businessinformation/rest
class GoogleBusinessProfileApi extends GoogleApi implements GoogleBusinessProfileApiInterface
{
    public const API_MYBUSINESS = 'https://mybusiness.googleapis.com/v4';
    public const API_MANAGEMENT = 'https://mybusinessaccountmanagement.googleapis.com/v1';
    public const API_INFORMATION = 'https://mybusinessbusinessinformation.googleapis.com/v1';

    public const FIELDS_LOCATION = [
        'name',
        'languageCode',
        'storeCode',
        'title',
        'categories',
        'storefrontAddress',
        'phoneNumbers',
        'websiteUri',
        'labels',
        'latlng',
        'metadata',
        'regularHours',
        'specialHours',
        'moreHours'
    ];

    public function accounts(array $args = []): array
    {
        $result = $this->get(self::API_MANAGEMENT . '/accounts', $args);

        return $result['accounts'] ?? [];
    }

    public function location(string $location, array $args = []): array
    {
        $result = $this->get(
            self::API_INFORMATION . "/$location",
            array_merge($args, [
                'readMask' => implode(',', self::FIELDS_LOCATION),
            ])
        );

        return $result;
    }

    public function locations(string $account, array $args = []): array
    {
        $result = $this->get(
            self::API_INFORMATION . "/$account/locations",
            array_merge($args, [
                'readMask' => 'name,title',
            ])
        );

        return $result['locations'] ?? [];
    }

    public function review(string $review): array
    {
        return $this->get(self::API_MYBUSINESS . "/$review");
    }

    public function reviews(string $account, string $location, array $args = []): array
    {
        return $this->get(self::API_MYBUSINESS . "/$account/$location/reviews", $args);
    }

    public function media(string $account, string $location, array $args = []): array
    {
        return $this->get(self::API_MYBUSINESS . "/$account/$location/media", $args);
    }

    public function posts(string $account, string $location, array $args = []): array
    {
        return $this->get(self::API_MYBUSINESS . "/$account/$location/localPosts", $args);
    }
}
