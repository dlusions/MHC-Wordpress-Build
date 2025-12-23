<?php
/**
 * @package DJ-SectionsAnywhere
 * @copyright Copyright (C) 2017  DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 *
 * DJContentFilters is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJContentFilters is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJContentFilters. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace DJExtensions\Yootheme\DJToggle;


return [
    'title' => 'DJ-Popup',
    'width' => 500,
    'heading' => false,
    'defaults' => [
        'djpopup.enabled' => 0,
        'djpopup.heading' => '',
        'djpopup.close_icon' => 'close',
        'djpopup.save_text' => '',
        'djpopup.save_link' => '',
        'djpopup.cancel_text' => '',
        'djpopup.width' => '',
        'djpopup.height' => '',
        'djpopup.position_left' => '',
        'djpopup.position_right' => '',
        'djpopup.position_top' => '',
        'djpopup.position_bottom' => '',
        'djpopup.padding' => '',
        'djpopup.fullscreen' => 0,
        'djpopup.save_style' => 'primary',
        'djpopup.cancel_style' => 'danger',
    ],
    'fields' => [
        'djpopup.heading' => [
            'label' => 'Heading',
            'type' => 'text',
            'enable' => 'djpopup.enabled'
        ],
        'djpopup.enabled' => [
            'text' => 'Convert this section to a popup',
            'label' => 'Enabled',
            'type' => 'checkbox',
            'default' => 0,
        ],
        'djpopup.close_icon' => [
            'label' => 'Close icon',
            'description' => 'Pick an optional icon from the icon library.',
            'type' => 'icon',
            'default' => '',
            'enable' => 'djpopup.enabled'
        ],
        'djpopup.save_text' => [
            'label' => 'Save text',
            'description' => 'Set the content of the button responsible for Save',
            'type' => 'text',
            'default' => '',
            'enable' => 'djpopup.enabled'
        ],
        'djpopup.save_link' => [
            'label' => 'Save link',
            'description' => 'Set the link the user is redirected to after clicking Save',
            'type' => 'link',
            'default' => '',
            'enable' => 'djpopup.enabled && djpopup.save_text'
        ],
        'djpopup.cancel_text' => [
            'label' => 'Cancel text',
            'description' => 'Set the content of the button responsible for Cancel',
            'type' => 'text',
            'default' => '',
            'enable' => 'djpopup.enabled'
        ],
        'djpopup.width' => [
            'label' => 'Width',
            'type' => 'range',
            'default' => '',
            'enable' => '!djpopup.fullscreen && djpopup.enabled',
        ],
        'djpopup.height' => [
            'label' => 'Height',
            'type' => 'range',
            'default' => '',
            'enable' => '!djpopup.fullscreen && djpopup.enabled',
        ],
        'djpopup.position_left' => [
            'label' => 'Left',
            'type' => 'range',
            'default' => '',
            'enable' => '!djpopup.fullscreen && djpopup.enabled',
        ],
        'djpopup.position_right' => [
            'label' => 'Right',
            'type' => 'range',
            'default' => '',
            'enable' => '!djpopup.fullscreen && djpopup.enabled',
        ],
        'djpopup.position_top' => [
            'label' => 'Top',
            'type' => 'range',
            'default' => '',
            'enable' => '!djpopup.fullscreen && djpopup.enabled',
        ],
        'djpopup.position_bottom' => [
            'label' => 'Bottom',
            'type' => 'range',
            'default' => '',
            'enable' => '!djpopup.fullscreen && djpopup.enabled',
        ],
        'djpopup.padding' => [
            'label' => 'Padding',
            'description' => 'Set the padding.',
            'type' => 'select',
            'options' => [
                'None' => 'remove',
                'Small' => 'small',
                'Default' => '',
                'Medium' => 'medium',
                'Large' => 'large',
            ],
            'default' => '',
            'enable' => '!djpopup.fullscreen && djpopup.enabled'
        ],
        'djpopup.fullscreen' => [
            'text' => 'Display in full screen mode',
            'label' => 'Fullscreen',
            'type' => 'checkbox',
            'default' => 0
        ],
        'djpopup.save_style' => [
            'label' => 'Save button style',
            'enable' => 'djpopup.enabled && djpopup.save_text',
            'type' => 'select',
            'default' => 'primary',
            'options' => [
                'Primary' => 'primary',
                'Secondary' => 'secondary',
                'Default' => 'default',
                'Danger' => 'danger',
                'link-muted' => 'link-muted',
                'link-text' => 'link-text',
            ],
        ],
        'djpopup.cancel_style' => [
            'label' => 'Cancel button style',
            'type' => 'select',
            'enable' => 'djpopup.enabled && djpopup.cancel_text',
            'default' => 'danger',
            'options' => [
                'Primary' => 'primary',
                'Secondary' => 'secondary',
                'Default' => 'default',
                'Danger' => 'danger',
                'link-muted' => 'link-muted',
                'link-text' => 'link-text',
            ],
        ],
        'djpopup.heading_style' => [
            'label' => 'Header style',
            'type' => 'select',
            'default' => 'h3',
            'options' => [
                'Default' => '',
                'h1' => 'h1',
                'h2' => 'h2',
                'h3' => 'h3',
                'h4' => 'h4',
                'h5' => 'h5',
                'h6' => 'h6',
            ],
            'enable' => '!djpopup.fullscreen && djpopup.enabled && djpopup.heading'
        ],
        'djpopup.triggers' => [
            'label' => 'Triggers',
            'type' => 'DJPopupTriggers',
            'enable' => 'djpopup.enabled',
            'description' => 'You can use triggers to determine at which point the popup should be displayed to the user',
            'item' => 'djpopup_trigger'
        ],
        'djpopup.animation' => [
            'label' => 'Animation',
            'type' => 'select',
            'options' => [
                'Default' => '',
                'Fade' => 'fade',
                'Scale up' => 'scale-up',
                'Scale down' => 'scale-down',
                'Shake' => 'shake',
                'Slide top' => 'slide-top',
                'Slide bottom' => 'slide-bottom',
                'Slide left' => 'slide-left',
                'Slide right' => 'slide-right',
                'Scale' => 'scale',
                'Kenburns' => 'kenburns',
            ],
            'enable' => 'djpopup.enabled'
        ],
        'djpopup.overlay' => [
            'label' => 'Overlay color',
            'type' => 'color',
            'enable' => 'djpopup.enabled',
            'default' => ''
        ],
        'djpopup.direction' => [
            "type" => "select",
            "label" => "Popup open direction",
            "options" => [
                "top center" => "top center",
                "top right" => "top right",
                "top left" => "top left",
                "center left" => "center left",
                "center right" => "center right",
                "center center" => "center center",
                "bottom center" => "bottom center",
                "bottom right" => "bottom right",
                "bottom left" => "bottom left",
            ],
            "default" => "top left",
        ],
        'djpopup.margin' => [
            'label' => 'Position margin',
            'type' => 'number',
        ],
        'djpopup.repeat' => [
            'label' => 'Repeat popup',
            'type' => 'select',
            'options' => [
                'Always' => 'always',
                'First time only' => 'first_time_only',
                'After x time' => 'after_x',
            ],
            'default' => 'always'
        ],
        'djpopup.repeatA.afterxmins' => [
            'label' => 'Repeat after x time in format DD:HH:MM',
            'type' => 'text',
            'description' => 'provide information in format DD:HH:MM',
            'show' => "djpopup.repeat=='after_x'"
        ]
    ],
    'fieldset' => [
        'default' => [
            'type' => 'tabs',
            'fields' => [
                [
                    'title' => 'Content',
                    'fields' => [
                        'djpopup.enabled',
                        'djpopup.heading',
                        'djpopup.close_icon',
                        'djpopup.save_text',
                        'djpopup.save_link',
                        'djpopup.cancel_text',
                    ]
                ],
                [
                    'title' => 'Settings',
                    'fields' => [
                        [
                            'label' => 'Heading',
                            'type' => 'group',
                            'fields' => [
                                'djpopup.heading_style',

                            ]
                        ],
                        [
                            'label' => 'Repeat',
                            'type' => 'group',
                            'fields' => [
                                'djpopup.repeat',
                                'djpopup.repeatA.afterxmins'
                            ]
                        ],
                        [
                            'label' => 'Direction',
                            'type' => 'group',
                            'fields' => [
                                'djpopup.direction',
                                'djpopup.margin'
                            ]
                        ],
                        [
                            'label' => 'Popup',
                            'type' => 'group',
                            'fields' => [
                                'djpopup.overlay',
                                'djpopup.animation',
                                'djpopup.fullscreen',
                                'djpopup.width',
                                'djpopup.height',
                                'djpopup.padding',
                                'djpopup.position_left',
                                'djpopup.position_right',
                                'djpopup.position_top',
                                'djpopup.position_bottom',
                            ]
                        ], [
                            'label' => 'Footer',
                            'type' => 'group',
                            'fields' => [
                                'djpopup.save_style',
                                'djpopup.cancel_style',
                            ]
                        ],

                    ]
                ],
                [
                    'title' => 'Advanced',
                    'fields' => [
                        'djpopup.triggers'
                    ]
                ],
            ]
        ]
    ]
];
