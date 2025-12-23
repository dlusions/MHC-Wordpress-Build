<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form;

use YOOtheme\Config;

class FormIdTransform
{
    private Config $config;

    private static $generatedFormIds = [];

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function presave($node, array $params): void
    {
        if (!$this->isFormArea($node)) {
            return;
        }

        $node->formid = null;
        $this->generateFormId($node, $params);
    }

    public function preload($node, array $params): void
    {
        if (!$this->isFormArea($node)) {
            return;
        }

        if ($this->config->get('app.isCustomizer')) {
            $node->formid = null;
        }

        $this->generateFormId($node, $params);
    }

    private function isFormArea($node): bool
    {
        return $node->props['yooessentials_form']->state ?? false;
    }

    private function generateFormId($node, array $params): void
    {
        $formid = $node->formid ?? null;

        if ($formid) {
            return;
        }

        if (!$formid) {
            // Same Form content, with same parent, same index and on the same url
            $formid = hash(
                'crc32b',
                json_encode([
                    parse_url($this->config->get('req.href'), PHP_URL_PATH),
                    $params['parent']->id ?? 0,
                    $params['i'] ?? 0,
                    json_encode($node),
                ])
            );
        }

        while (in_array($formid, self::$generatedFormIds, true)) {
            $formid .= '-1';
        }

        self::$generatedFormIds[] = $formid;

        $node->formid = $formid;
    }
}
