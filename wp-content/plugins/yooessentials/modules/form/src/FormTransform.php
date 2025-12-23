<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form;

use YOOtheme\Builder;

class FormTransform
{
    private FormConfigRepository $configRepository;

    private Builder $builder;

    public function __construct(Builder $builder, FormConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
        $this->builder = $builder;
    }

    public function __invoke($node, array $params)
    {
        if ($this->isFormNode($node)) {
            $this->createForm($node);
        }
    }

    protected function createForm($node): ?Form
    {
        $config = (array) $node->props['yooessentials_form'];
        $formNode = $this->wrap($node, $config);
        $config['controls'] = $this->getControls($formNode);

        $this->configRepository->saveConfig($formNode->id, $config);

        return new Form($formNode->id, $config);
    }

    protected function wrap($node, array $config)
    {
        // pass through the config so in the first rendering there is the basic config available
        $formNode = $this->loadFormNode($node->formid, $config);

        $formNode->form->config = $config;
        $formNode->children = $node->children;

        $node->children = [$formNode];

        return $formNode;
    }

    private function loadFormNode(string $id, array $config): ?object
    {
        // pass through the config so in the first rendering there is the basic config available
        return $this->builder->load(
            json_encode([
                'id' => $id,
                'type' => 'yooessentials_form',
                'yooessentials_form' => $config,
            ]),
            ['context' => 'render']
        );
    }

    private function isFormNode($node): bool
    {
        return ($node->props['yooessentials_form']->state ?? false) && ($node->formid ?? false);
    }

    private function getControls($node): array
    {
        return array_reduce(
            $node->children,
            function ($carry, $node) {
                $type = $this->builder->types[$node->type] ?? null;

                if (!$type) {
                    return $carry;
                }

                if ($type->submittable and ($node->controls ?? false)) {
                    foreach ($node->controls as $control) {
                        $carry[] = array_merge(['type' => $node->type], $control);
                    }
                }

                if ($node->children ?? false) {
                    $carry = array_merge($carry, $this->getControls($node));
                }

                return $carry;
            },
            []
        );
    }
}
