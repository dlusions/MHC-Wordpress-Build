<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action;

class ActionManager
{
    private $actions = [];

    public function actionClass(string $action): ?Action
    {
        return $this->actions[$action] ?? null;
    }

    public function actionFromConfig(array $config): ?ActionInstance
    {
        $action = $config['type'] ?? null;
        if (!$action) {
            return null;
        }

        $actionClass = $this->actionClass($action);
        if (!$actionClass) {
            return null;
        }

        return new ActionInstance($actionClass, $config, $config['id'] ?? null);
    }

    public function action(string $action, array $config, ?string $id = null): ?ActionInstance
    {
        $actionClass = $this->actionClass($action);
        if (!$actionClass) {
            return null;
        }

        return new ActionInstance($actionClass, $config, $id);
    }

    public function actions(): array
    {
        return $this->actions;
    }

    public function reset(): self
    {
        $this->actions = [];

        return $this;
    }

    public function addAction(string $actionName, string $actionClass, array $config): self
    {
        $this->actions[$actionName] = new $actionClass($config);

        return $this;
    }

    public function actionFromClassName(string $className): ?Action
    {
        $actions = array_filter($this->actions, fn (Action $action) => $action instanceof $className);

        return array_shift($actions) ?? null;
    }
}
