<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Rule\Geolocation;

use ZOOlanders\YOOessentials\Vendor\GeoIp2\Model\City;
use ZOOlanders\YOOessentials\Vendor\GeoIp2\Model\Country;
use function YOOtheme\app;
use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Condition\ConditionRule;
use ZOOlanders\YOOessentials\Api\MaxMind\GeoIp;
use ZOOlanders\YOOessentials\Util;

class GeolocationRule extends ConditionRule
{
    protected GeoIp $geoip;

    public function __construct(array $data)
    {
        $this->geoip = app(GeoIp::class);

        parent::__construct($data);
    }

    public function resolve($props, $node): bool
    {
        $continents = self::parseTextareaList($props->continents ?? '');
        $countries = self::parseTextareaList($props->countries ?? '');
        $cities = self::parseTextareaList($props->cities ?? '');
        $codes = self::parseTextareaList($props->codes ?? '');

        if (!$this->geoip->isReaderReady()) {
            throw new \RuntimeException('GeoIp Database Error');
        }

        if (!array_filter(array_merge($countries, $continents, $cities, $codes))) {
            throw new \RuntimeException('Not Valid Evaluation Arguments');
        }

        $ip = Util\Ip::getIP();
        $result = [];

        if (($continents || $countries) && ($record = $this->geoip->getCountryRecord($ip))) {
            $continentData = $record->continent->jsonSerialize() ?? [];
            $continentData = array_filter([$continentData['code'] ?? null] + array_values($continentData['names'] ?? []));

            $countryData = $record->country->jsonSerialize() ?? [];
            $countryData = array_filter([$countryData['iso_code'] ?? null] + array_values($countryData['names'] ?? []));

            $result['continents'] = $this->resolveEntries($continents, $continentData);
            $result['countries'] = $this->resolveEntries($countries, $countryData);
        }

        if (($cities || $codes) && ($record = $this->geoip->getCityRecord($ip))) {
            $cityData = $record->city->jsonSerialize() ?? [];
            $cityData = array_filter([$cityData['name'] ?? null] + array_values($cityData['names'] ?? []));

            $codeData = [$record->postal->code];

            $result['cities'] = $this->resolveEntries($cities, $cityData);
            $result['codes'] = $this->resolveEntries($codes, $codeData);
        }

        return (bool) array_filter($result);
    }

    protected function resolveEntries(array $entries, array $data): bool
    {
        return Arr::some($entries, fn ($entry) => in_array($entry, $data));
    }

    public function logArgs(object $props): array
    {
        $ip = Util\Ip::getIP();

        /** @var Country|false|null $countryRecord */
        $countryRecord = $this->geoip->getCountryRecord($ip);

        /** @var City|false|null $cityRecord */
        $cityRecord = $this->geoip->getCityRecord($ip);

        $continentData = [];
        $countryData = [];
        $cityData = [];
        $codeData = [];

        if ($countryRecord) {
            $continent = $countryRecord->continent ?: null;
            $continentData = $continent ? $continent->jsonSerialize() : [];
            $continentData = array_filter([$continentData['code'] ?? null] + array_values($continentData['names'] ?? []));

            $country = $countryRecord->country ?: null;
            $countryData = $country ? $country->jsonSerialize() : [];
            $countryData = array_filter([$countryData['iso_code'] ?? null] + array_values($countryData['names'] ?? []));
        }

        if ($cityRecord) {
            $city = $cityRecord->city ?: null;
            $cityData = $city ? $city->jsonSerialize() : [];
            $cityData = array_filter([$cityData['name'] ?? null] + array_values($cityData['names'] ?? []));

            $codeData = array_filter([$cityRecord->postal ? $cityRecord->postal->code : null]);
        }

        return [
            'IP' => $ip,
            'IP Country' => array_values($countryData),
            'IP City' => array_values($cityData),
            'IP Continent' => array_values($continentData),
            'IP Postal Code' => array_values($codeData),
        ];
    }
}
