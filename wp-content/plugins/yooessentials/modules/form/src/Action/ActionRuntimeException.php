<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action;

use Exception;
use RuntimeException;

class ActionRuntimeException extends RuntimeException
{
    private Action $action;

    public static function create(Action $action, string $error, ?Exception $previous = null): self
    {
        $actionName = (new \ReflectionClass($action))->getShortName();
        $message = "{$actionName} execution error: {$error}";

        return (new ActionRuntimeException($message, $previous ? $previous->getCode() : 0, $previous))
            ->forAction($action);
    }

    public function forAction(Action $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function action(): Action
    {
        return $this->action;
    }
}
