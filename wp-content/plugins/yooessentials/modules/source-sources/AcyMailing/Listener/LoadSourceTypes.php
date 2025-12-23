<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\AcyMailing\Listener;

use ZOOlanders\YOOessentials\Api\AcyMailing\AcyMailingApi;
use ZOOlanders\YOOessentials\Source\Provider\AcyMailing\Type;

class LoadSourceTypes
{
    public function handle($source): void
    {
        if (!AcyMailingApi::helperPath()) {
            return;
        }

        $query = [
            Type\AcyMailingCustomListsQueryType::config(),
            Type\AcyMailingCustomSubscriberQueryType::config()
        ];

        $types = [
            [Type\AcyMailingListType::NAME, Type\AcyMailingListType::config()],
            [Type\AcyMailingSubscriberType::NAME, Type\AcyMailingSubscriberType::config()],
            [Type\AcyMailingSubscriptionType::NAME, Type\AcyMailingSubscriptionType::config()]
        ];

        foreach ($query as $args) {
            $source->queryType($args);
        }

        foreach ($types as $args) {
            $source->objectType(...$args);
        }
    }
}
