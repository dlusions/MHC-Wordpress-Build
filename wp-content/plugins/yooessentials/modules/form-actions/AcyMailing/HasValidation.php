<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\AcyMailing;

use YOOtheme\Arr;
use ZOOlanders\YOOessentials\Form\Action\ActionConfigurationException;

trait HasValidation
{
    protected function validateConfig(object $config)
    {
        if (isset($config->lists)) {
            if (!is_array($config->lists)) {
                throw new ActionConfigurationException('Invalid lists provided, expected array.');
            }

            foreach ($config->lists as $list) {
                if (!is_numeric($list)) {
                    throw new ActionConfigurationException('Invalid lists provided, numeric items expected.');
                }
            }
        }
    }

    protected function validateSubscriber(object $subscriber)
    {
        if (!($subscriber->email ?? null)) {
            throw new \Exception('Invalid subscriber email address.');
        }
    }

    protected function validateCustomFields(array $customFields, array $fieldsConfig)
    {
        // validate custom fields data
        foreach ($customFields as $id => $value) {
            $fieldConfig = $fieldsConfig[$id] ?? null;

            if (!$fieldConfig) {
                continue;
            }

            if ($fieldConfig->type === 'radio' || $fieldConfig->type === 'single_dropdown') {
                $values = array_column($fieldConfig->value, 'value');

                if (!in_array($value, $values)) {
                    throw new ActionConfigurationException("Invalid value for field {$fieldConfig->name}");
                }
            }

            if ($fieldConfig->type === 'checkbox' || $fieldConfig->type === 'multiple_dropdown') {
                $values = array_column($fieldConfig->value, 'value');

                foreach (Arr::wrap($value) as $val) {
                    if (!in_array($val, $values)) {
                        throw new ActionConfigurationException("Invalid value for field {$fieldConfig->name}");
                    }
                }
            }
        }

        // validate custom fields required
        foreach ($fieldsConfig as $id => $fieldConfig) {
            if ($fieldConfig->required && !isset($customFields[$id])) {
                throw new \Exception("Custom Field {$fieldConfig->name} is required.");
            }
        }
    }
}
