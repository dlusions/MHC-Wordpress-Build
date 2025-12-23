<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Condition\Listener;

use YOOtheme\Config;
use YOOtheme\Metadata;
use YOOtheme\Path;
use ZOOlanders\YOOessentials\Condition\ConditionManager;
use ZOOlanders\YOOessentials\Condition\ConditionRuleInterface;

class LoadCustomizerData
{
    public Config $config;
    public Metadata $metadata;
    public ConditionManager $manager;

    public function __construct(Config $config, Metadata $metadata, ConditionManager $manager)
    {
        $this->config = $config;
        $this->metadata = $metadata;
        $this->manager = $manager;
    }

    public function handle(): void
    {
        $this->config->addFile('customizer', Path::get('../../config/customizer.json'));
        $conditionPanelRule = $this->config->loadFile(Path::get('../../config/condition-rule-panel.json'));

        $rules = array_map(function (ConditionRuleInterface $rule) use ($conditionPanelRule) {
            $panel = array_merge($conditionPanelRule, [
                'title' => $rule->title(),
                'group' => $rule->group(),
                'icon' => $rule->icon(),
            ]);

            $panel['fields']['_rule']['fields'] = $rule->fields();

            return [
                'name' => $rule->name(),
                'title' => $rule->title(),
                'group' => $rule->group(),
                'collection' => $rule->collection(),
                'collectionDescription' => $rule->collectionDescription ?? '',
                'description' => $rule->description(),
                'icon' => $rule->icon(),
                'panel' => $panel,
            ];
        }, $this->manager->rules());

        $this->config->set('yooessentials.customizer.condition_rules', $rules);
    }
}
