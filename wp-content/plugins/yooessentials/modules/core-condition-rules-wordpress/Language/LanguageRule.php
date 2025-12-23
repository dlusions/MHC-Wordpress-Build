<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Wordpress\Rule\Language;

use ZOOlanders\YOOessentials\Condition\ConditionRule;

class LanguageRule extends ConditionRule
{
    public function resolve($props, $node): bool
    {
        if (!isset($props->languages)) {
            throw new \RuntimeException('Not Valid Input');
        }

        $current = \determine_locale();
        $selection = (array) $props->languages;

        return in_array($current, $selection);
    }

    public function fields(): array
    {
        return [
            'languages' => [
                'label' => 'Selection',
                'type' => 'select',
                'source' => true,
                'description' => 'The languages that the site current language must match. Use the shift or ctrl/cmd key to select multiple entries.',
                'attrs' => [
                    'multiple' => true,
                    'class' => 'uk-height-small uk-resize-vertical',
                ],
                'options' => $this->getAvailableLanguages(),
            ],
        ];
    }

    public function logArgs(object $props): array
    {
        return [
            'Active Language' => \determine_locale(),
        ];
    }

    protected function getAvailableLanguages(): array
    {
        static $languages = [];

        if (!empty($languages)) {
            return $languages;
        }

        // support for WPML
        if (defined('ICL_SITEPRESS_VERSION')) {
            $languages = array_merge($languages, $this->getWmplLanguages());
        } else {
            $languages = array_merge($languages, $this->getCoreLanguages());
        }

        return $languages;
    }

    protected function getCoreLanguages(): array
    {
        require_once \ABSPATH . 'wp-admin/includes/translation-install.php';

        $languages = [];
        $languages['English (United States)'] = 'en_US';

        $availableLanguages = \get_available_languages();
        $translations = \wp_get_available_translations();

        foreach ($availableLanguages as $language) {
            $name = $translations[$language]['native_name'] ?? $language;
            $languages[$name] = $language;
        }

        return $languages;
    }

    protected function getWmplLanguages(): array
    {
        $languages = [];
        $availableLanguages = \icl_get_languages('skip_missing=0');

        foreach ($availableLanguages as $language) {
            $languages[$language['native_name']] = $language['default_locale'];
        }

        return $languages;
    }
}
