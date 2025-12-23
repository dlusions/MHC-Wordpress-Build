<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\AcyMailing;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Form\Action\StandardAction;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;
use ZOOlanders\YOOessentials\Form\Action\ActionConfigurationException;
use ZOOlanders\YOOessentials\Api\AcyMailing\AcyMailingApiInterface;
use ZOOlanders\YOOessentials\Util;

class AcyMailingUnsubscribeAction extends StandardAction
{
    use HasValidation;

    public const NAME = 'acymailing-unsubscribe';

    public function __invoke(FormSubmissionResponse $response, callable $next): FormSubmissionResponse
    {
        /** @var AcyMailingApiInterface $acyMailing */
        $acyMailing = app(AcyMailingApiInterface::class);

        $config = self::parseConfig((object) $this->getConfig());
        $subscriber = (object) Util\Prop::filterByPrefix((array) $config, 'subscriber_');

        try {
            $this->validateConfig($config);
            $this->validateSubscriber($subscriber);
        } catch (\Throwable $e) {
            throw ActionConfigurationException::create($this, $e->getMessage(), $e);
        }

        $subscriber = $acyMailing->getSubscriberByEmail($subscriber->email);

        if (isset($subscriber->id) && !empty($config->lists)) {
            $acyMailing->unsubscribe($subscriber->id, $config->lists);
        }

        return $next(
            $response->withDataLog([
                self::NAME => $config,
            ])
        );
    }

    protected static function parseConfig(object $config): object
    {
        $config->lists ??= [];

        if (is_string($config->lists)) {
            $config->lists = Util\Arr::explodeList($config->lists);
        }

        return $config;
    }
}
