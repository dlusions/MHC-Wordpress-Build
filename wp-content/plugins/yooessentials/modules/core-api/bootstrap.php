<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api;

return [
    'events' => [
        'customizer.init' => [
            LoadCustomizerData::class => ['@handle', 10],
        ],
    ],

    'services' => [
        MaxMind\GeoIp::class => '',
        AcyMailing\AcyMailingApiInterface::class => AcyMailing\AcyMailingApi::class,
        Airtable\AirtableApiInterface::class => Airtable\AirtableApi::class,
        Bluesky\BlueskyApiInterface::class => Bluesky\BlueskyApi::class,
        Facebook\FacebookApiInterface::class => Facebook\FacebookApi::class,
        Google\GoogleBusinessProfileApiInterface::class => Google\GoogleBusinessProfileApi::class,
        Google\GoogleCalendarApiInterface::class => Google\GoogleCalendarApi::class,
        Google\GoogleDriveApiInterface::class => Google\GoogleDriveApi::class,
        Google\GooglePhotosApiInterface::class => Google\GooglePhotosApi::class,
        Google\GoogleSheetsApiInterface::class => Google\GoogleSheetsApi::class,
        Google\YouTubeApiInterface::class => Google\YouTubeApi::class,
        Instagram\InstagramApiInterface::class => Instagram\InstagramApi::class,
        LinkedIn\LinkedInApiInterface::class => LinkedIn\LinkedInApi::class,
        TikTok\TikTokApiInterface::class => TikTok\TikTokApi::class,
        Twitter\TwitterApiInterface::class => Twitter\TwitterApi::class,
        Vimeo\VimeoApiInterface::class => Vimeo\VimeoApi::class,
        Mailchimp\MailchimpApiInterface::class => Mailchimp\MailchimpApi::class,
    ],
];
