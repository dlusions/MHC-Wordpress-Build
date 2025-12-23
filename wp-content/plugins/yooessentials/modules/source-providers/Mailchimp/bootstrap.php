<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Mailchimp;

return [
    'routes' => [
        ['post', MailchimpSourceController::PRESAVE_ENDPOINT, MailchimpSourceController::class . '@presave'],
        ['post', MailchimpSourceController::AUDIENCE_LISTS_ENDPOINT, MailchimpSourceController::class . '@lists'],
        ['post', MailchimpSourceController::INTEREST_CATEGORIES_ENDPOINT, MailchimpSourceController::class . '@interestCategories'],
    ],

    'yooessentials-sources' => [
        'mailchimp-audience' => MailchimpSource::class,
    ],
];
