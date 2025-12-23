<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form;

class ControlTransform
{
    /**
     * Transform callback.
     *
     * @param object $node
     * @param array  $params
     */
    public function __invoke($node, array $params)
    {
        if (!($params['type']->submittable ?? false)) {
            return true;
        }

        /**
         * @var $type
         */
        extract($params);

        $type = $params['type'];

        if (is_array($type->controls ?? null)) {
            $node->controls = new \stdClass();

            foreach ($type->controls as $name => $control) {
                if (is_callable($control)) {
                    $control = $control($node, $params);

                    // make name safe
                    $control['name'] = trim($control['name'] ?? ($control['props']['name'] ?? ''));

                    $node->controls->$name = $control;
                }
            }
        }

        return null;
    }
}
