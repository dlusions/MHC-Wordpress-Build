<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Request\Type;

use YOOtheme\Arr;

class RequestUrlType
{
    public static function config(): array
    {
        return [
            'fields' => [
                'query_param' => [
                    'type' => 'String',
                    'args' => [
                        'param' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'Param',
                        'arguments' => [
                            'param' => [
                                'label' => 'Param Name',
                                'description' => 'Set the query param name or path which value to return, e.g. `my-param` or `my-param.0`.',
                                'default' => '',
                                'required' => true,
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveQueryParam',
                    ],
                ],
                'query' => [
                    'type' => 'String',
                    'args' => [
                        'param' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'Query',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveQuery',
                    ],
                ],
                'scheme' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Scheme',
                    ],
                ],

                'host' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Host',
                    ],
                ],

                'port' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Port',
                    ],
                ],

                'path' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Path',
                    ],
                ],

                'fragment' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Fragment',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => 'Request URL',
            ],
        ];
    }

    public static function resolveQuery(array $url, array $args): string
    {
        $query = $url['query'] ?? '';
        $param = $args['param'] ?? '';

        /** Deprecated since 1.5.4 */
        if ($param && $query) {
            parse_str($query, $parts);
            $result = Arr::get($parts, $param);

            if (is_array($result)) {
                return http_build_query($result);
            }

            return $result;
        }

        return $query;
    }

    public static function resolveQueryParam(array $url, array $args): ?string
    {
        $query = $url['query'] ?? '';
        $param = $args['param'] ?? '';

        if (!$query || !$param) {
            return null;
        }

        parse_str($query, $parts);
        $result = Arr::get($parts, $param);

        if (is_array($result)) {
            return http_build_query($result);
        }

        return $result;
    }
}
