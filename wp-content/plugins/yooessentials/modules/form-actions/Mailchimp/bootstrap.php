<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Mailchimp;

return [
    'routes' => [
        ['post', MailchimpActionController::LISTS_ENDPOINT, MailchimpActionController::class . '@lists'],
        ['post', MailchimpActionController::MERGE_FIELDS_ENDPOINT, MailchimpActionController::class . '@mergeFields'],
        ['post', MailchimpActionController::INTERESTS_ENDPOINT, MailchimpActionController::class . '@interests'],
        ['post', MailchimpActionController::MARKETING_PERMISSIONS_ENDPOINT, MailchimpActionController::class . '@marketingPermissions']
    ],

    'yooessentials-form-actions' => [
        MailchimpActionUpsertMember::class => __DIR__ . '/member-upsert.json',
        MailchimpActionRemoveMember::class => __DIR__ . '/member-remove.json'
    ],
];
