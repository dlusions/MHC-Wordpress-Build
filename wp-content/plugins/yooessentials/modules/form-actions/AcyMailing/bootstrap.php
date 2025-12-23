<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\AcyMailing;

return [

    'routes' => [
        ['post', AcyMailingController::LISTS_ENDPOINT, AcyMailingController::class . '@listsField'],
        ['post', AcyMailingController::CUSTOM_FIELDS_ENDPOINT, AcyMailingController::class . '@customFields'],
    ],

    'yooessentials-form-actions' => [
        AcyMailingSubscribeAction::class => __DIR__ . '/action-subscribe.json',
        AcyMailingUnsubscribeAction::class => __DIR__ . '/action-unsubscribe.json'
    ],

];
