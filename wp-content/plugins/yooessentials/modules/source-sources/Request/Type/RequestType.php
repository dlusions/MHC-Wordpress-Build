<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Request\Type;

use function YOOtheme\app;
use YOOtheme\Http\Request;

class RequestType
{
    public static function config(): array
    {
        return [
            'fields' => [
                'time' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Timestamp',
                        'filters' => ['date'],
                        'required' => true,
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveTime',
                    ],
                ],
                'href' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Href',
                    ],
                ],
                'ip' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'IP',
                    ],
                ],
                'method' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Method',
                    ],
                ],
                'origin' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Origin',
                    ],
                ],
                'useragent' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Useragent',
                    ],
                ],
                'referer' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Referer',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveReferer',
                    ],
                ],
                'url' => [
                    'type' => 'YooessentialsRequestUrl',
                    'metadata' => [
                        'label' => 'Url',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveUrl',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => 'Request',
            ],
        ];
    }

    public static function resolveUrl(array $request): array
    {
        return parse_url($request['href'] ?? []);
    }

    public static function resolveReferer(): ?string
    {
        $request = app(Request::class);
        $header = $request->getHeader('Referer');

        return !empty($header) ? array_shift($header) : null;
    }

    public static function resolveTime(array $request): int
    {
        return round($request['time'] ?? 0);
    }
}
