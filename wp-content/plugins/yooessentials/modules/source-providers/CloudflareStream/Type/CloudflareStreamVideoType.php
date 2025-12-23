<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\CloudflareStream\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;
use ZOOlanders\YOOessentials\Util;

class CloudflareStreamVideoType extends GenericType
{
    public const NAME = 'CloudflareStreamVideo';
    public const LABEL = 'Video';

    public function config(): array
    {
        return [
            'fields' => [
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Name',
                        'filters' => ['limit'],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::streamName',
                    ],
                ],
                'iframe_url' => [
                    'type' => 'String',
                    'args' => [
                        'autoplay' => [
                            'type' => 'Boolean',
                        ],
                        'loop' => [
                            'type' => 'Boolean',
                        ],
                        'muted' => [
                            'type' => 'Boolean',
                        ],
                        'controls' => [
                            'type' => 'Boolean',
                        ],
                        'time' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'Iframe URL',
                        'arguments' => [
                            'autoplay' => [
                                'label' => 'Autoplay',
                                'type' => 'checkbox',
                                'text' => 'Enable',
                                'description' => 'Tells the browser to immediately start downloading the video and play it as soon as it can.',
                            ],
                            'loop' => [
                                'label' => 'Loop',
                                'type' => 'checkbox',
                                'text' => 'Enable',
                                'description' => 'If enabled the player will automatically seek back to the start upon reaching the end of the video.',
                            ],
                            'muted' => [
                                'label' => 'Muted',
                                'type' => 'checkbox',
                                'text' => 'Enable',
                                'description' => 'If set, the audio will be initially silenced.',
                            ],
                            'controls' => [
                                'label' => 'Controls',
                                'type' => 'checkbox',
                                'text' => 'Enable',
                                'default' => true,
                                'description' => 'Shows video controls such as buttons for play/pause, volume controls.',
                            ],
                            'time' => [
                                'label' => 'Time',
                                'description' => 'Time from the video, e.g. 5m2s.',
                                'attrs' => [
                                    'placeholder' => '0s',
                                ],
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::iframeUrl',
                    ],
                ],
                'preview_url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Preview URL',
                    ],
                ],
                'playback_url' => [
                    'type' => 'String',
                    'args' => [
                        'protocol' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => 'Playback URL',
                        'arguments' => [
                            'protocol' => [
                                'label' => 'Protocol',
                                'type' => 'select',
                                'default' => 'hls',
                                'options' => [
                                    'HLS' => 'hls',
                                    'DASH' => 'dash',
                                ],
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::playback',
                    ],
                ],
                'size' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Size',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::size',
                    ],
                ],
                'duration' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Duration (sec)',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::duration',
                    ],
                ],
                'width' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Width (px)',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::width',
                    ],
                ],
                'height' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => 'Height (px)',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::height',
                    ],
                ],
                'thumbnail' => self::thumnbailFieldConfig(),
                'thumbnail_animated' => self::thumnbailAnimatedFieldConfig(),
                'created' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Created At',
                        'filters' => ['date'],
                    ],
                ],
                'modified' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Modified At',
                        'filters' => ['date'],
                    ],
                ],
                'uploaded' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'Uploaded At',
                        'filters' => ['date'],
                    ],
                ],
                // 'state' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'State',
                //     ],
                //     'extensions' => [
                //         'call' => __CLASS__ . '::state',
                //     ],
                // ],
                // 'watermark' => [
                //     'type' => 'String',
                //     'metadata' => [
                //         'label' => 'Watermark',
                //     ],
                // ],
                // 'readyToStream' => [
                //     'type' => 'Boolean',
                //     'metadata' => [
                //         'label' => 'Ready To Stream',
                //     ],
                // ],
                // 'requireSignedURLs' => [
                //     'type' => 'Boolean',
                //     'metadata' => [
                //         'label' => 'Require Signed URLs',
                //     ],
                // ],
                // 'allowedOrigins' => [
                //     'type' => [
                //         'listOf' => 'String'
                //     ],
                //     'metadata' => [
                //         'label' => 'Allowed Origins',
                //     ],
                // ],

                'uid' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }

    protected static function thumnbailFieldConfig()
    {
        return [
            'type' => 'String',
            'args' => [
                'time' => [
                    'type' => 'String',
                ],
                'height' => [
                    'type' => 'String',
                ],
                'width' => [
                    'type' => 'String',
                ],
                'fit' => [
                    'type' => 'String',
                ],
            ],
            'metadata' => [
                'label' => 'Thumbnail',
                'arguments' => [
                    'time' => [
                        'label' => 'Time',
                        'description' => 'Time from the video, e.g. 5m2s.',
                        'attrs' => [
                            'placeholder' => '0s',
                        ],
                    ],
                    'height' => [
                        'label' => 'Height',
                        'attrs' => [
                            'placeholder' => 640,
                        ],
                    ],
                    'width' => [
                        'label' => 'Width',
                        'attrs' => [
                            'placeholder' => 640,
                        ],
                    ],
                    'fit' => [
                        'label' => 'Fit',
                        'description' => 'What to do when requested height and width doesn\'t match the original upload. Crop, cut parts of the video that doesn\'t fit in the given size. Clip, preserve the entire frame and decrease the size of the image within given size. Scale, distort the image to fit the given size. Fill, preserve the entire frame and fill the rest of the requested size with black background.',
                        'type' => 'select',
                        'default' => 'crop',
                        'options' => [
                            'Crop' => 'crop',
                            'Clip' => 'clip',
                            'Scale' => 'scale',
                            'Fill' => 'fill',
                        ],
                    ],
                ],
            ],
            'extensions' => [
                'call' => __CLASS__ . '::thumbnail',
            ],
        ];
    }

    protected static function thumnbailAnimatedFieldConfig()
    {
        $field = self::thumnbailFieldConfig();

        $field['args'] = array_merge($field['args'], [
            'duration' => [
                'type' => 'String',
            ],
            'fps' => [
                'type' => 'String',
            ],
        ]);

        $field['metadata']['label'] = 'Thumbnail (Animated GIF)';
        $field['metadata']['arguments'] = array_merge($field['metadata']['arguments'], [
            'duration' => [
                'label' => 'Duration',
                'description' => 'Duration of the animation in seconds, e.g. 3s.',
                'attrs' => [
                    'placeholder' => '5s',
                ],
            ],
            'fps' => [
                'label' => 'Frames Per Secons',
                'attrs' => [
                    'placeholder' => 8,
                ],
            ],
        ]);

        $field['extensions']['call'] = [
            'func' => __CLASS__ . '::thumbnail',
            'args' => [
                'format' => 'gif',
            ],
        ];

        return $field;
    }

    public static function streamName(array $stream): string
    {
        return $stream['meta']['name'] ?? '';
    }

    // public static function state(array $stream)
    // {
    //     return $stream['status']['state'] ?? '';
    // }

    public static function duration(array $stream): string
    {
        return Util\Date::humanize($stream['duration'] ?? 0);
    }

    public static function size(array $stream): string
    {
        return Util\File::formatBytes($stream['size'] ?? 0);
    }

    public static function height(array $stream): int
    {
        return $stream['input']['height'] ?? 0;
    }

    public static function width(array $stream): int
    {
        return $stream['input']['width'] ?? 0;
    }

    public static function iframeUrl(array $stream, array $args): string
    {
        $query = http_build_query($args, '', '&');

        return "https://iframe.videodelivery.net/{$stream['uid']}?$query";
    }

    public static function playback(array $stream, array $args): string
    {
        $protocol = $args['protocol'] ?? 'hls';

        return $stream['playback'][$protocol] ?? '';
    }

    public static function thumbnail(array $stream, array $args): string
    {
        $uid = $stream['uid'] ?? '';
        $format = $args['format'] ?? 'jpg';
        $query = http_build_query($args, '', '&');
        $url = "https://videodelivery.net/{$uid}/thumbnails/thumbnail.{$format}?$query";

        $cacheid = md5($url);

        return Util\File::cacheMedia($url, "cloudflare-media-$cacheid");
    }
}
