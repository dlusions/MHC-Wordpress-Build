<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Listener;

use YOOtheme\Config;
use ZOOlanders\YOOessentials\Form\Action\ActionManager;

class LoadCustomizerData
{
    public Config $config;
    public ActionManager $manager;

    public function __construct(Config $config, ActionManager $manager)
    {
        $this->config = $config;
        $this->manager = $manager;
    }

    public function handle(): void
    {
        $this->config->set('yooessentials.customizer.form_actions', $this->getActions());
    }

    protected function getActions()
    {
        $actions = [];

        foreach ($this->manager->actions() as $name => $action) {
            $actions[$action->name()] = array_merge($action->panel(), [
                'name' => $action->name(),
                'title' => $action->title(),
            ]);
        }

        return $actions;
    }
}
