<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Html;

use YOOtheme\View;
use YOOtheme\View\HtmlElement;

class HtmlHelper
{
    protected View $view;

    /**
     * Constructor.
     *
     * @param View $view
     */
    public function __construct(View $view)
    {
        $view['ye-form'] = $this;
    }

    /**
     * Renders form label.
     *
     * @return string
     */
    public function label(array $attrs = [], $contents = false)
    {
        $tag = isset($attrs['for']) ? 'label' : 'div';

        $attrs = array_merge_recursive(
            [
                'class' => ['uk-form-label'],
            ],
            $attrs
        );

        return new HtmlElement($tag, $attrs, $contents);
    }

    /**
     * Returns a form control Html instance.
     *
     * @return string
     */
    public function control($name, $label = null, $required = false, $id = null)
    {
        return new ControlElement($name, $label, $required, $id);
    }

    /**
     * Returns a form control fieldset Html instance.
     *
     * @return string
     */
    public function controlFieldset($name, $label = null, $required = false, $id = null)
    {
        return new ControlFieldsetElement($name, $label, $required, $id);
    }

    /**
     * Renders form input control.
     *
     * @return string
     */
    public function input(array $attrs = [])
    {
        $attrs = array_merge_recursive(
            [
                'class' => [
                    'uk-input',
                    'uk-form-{size}',
                    'uk-form-width-{width}',
                    'uk-form-blank {@blank}',
                    'uk-form-{state: danger|success}',
                ],
            ],
            $attrs
        );

        return new HtmlElement('input', $attrs);
    }

    /**
     * Renders form input icon.
     *
     * @return string
     */
    public function inputIcon(array $attrs = [])
    {
        $attrs = array_merge_recursive(
            [
                'class' => ['uk-form-icon', 'uk-form-icon-flip {@align: right}'],
                'uk-icon' => ['icon: {icon}'],
            ],
            $attrs
        );

        return new HtmlElement('span', $attrs);
    }

    /**
     * Renders form textarea control.
     *
     * @return string
     */
    public function textarea(array $attrs = [], $contents = false)
    {
        $attrs = array_merge_recursive(
            [
                'class' => [
                    'uk-textarea',
                    'uk-form-{size}',
                    'uk-form-width-{width}',
                    'uk-form-blank {@blank}',
                    'uk-form-{state: danger|success}',
                ],
            ],
            $attrs
        );

        return new HtmlElement('textarea', $attrs, $contents);
    }

    /**
     * Renders form select control.
     *
     * @return string
     */
    public function select(array $attrs = [], $contents = false)
    {
        $attrs = array_merge_recursive(
            [
                'class' => [
                    'uk-select',
                    'uk-form-{size}',
                    'uk-form-width-{width}',
                    'uk-form-blank {@blank}',
                    'uk-form-{state: danger|success}',
                ],
            ],
            $attrs
        );

        return new HtmlElement('select', $attrs, $contents);
    }

    /**
     * Renders form radio control.
     *
     * @return string
     */
    public function radio($name, array $attrs = [], $contents = '')
    {
        $attrs = array_merge_recursive($attrs, [
            'type' => 'radio',
            'name' => $name,
            'class' => ['uk-radio'],
        ]);

        $radio = new HtmlElement('input', $attrs, '');

        return "<label class=\"uk-flex\"><div>{$radio()}</div><div class=\"uk-margin-small-left\">{$contents}</div></label>";
    }

    /**
     * Renders form checkbox control.
     *
     * @return string
     */
    public function checkbox($name, array $attrs = [], $contents = '')
    {
        $attrs = array_merge_recursive($attrs, [
            'type' => 'checkbox',
            'name' => "{$name}[]",
            'class' => ['uk-checkbox'],
        ]);

        $checkbox = new HtmlElement('input', $attrs, '');

        return "<label class=\"uk-flex\"><div>{$checkbox()}</div><div class=\"uk-margin-small-left\">{$contents}</div></label>";
    }

    /**
     * Renders form range control.
     *
     * @return string
     */
    public function range(array $attrs = [])
    {
        $attrs = array_merge_recursive(
            [
                'type' => 'range',
                'class' => ['uk-range', 'uk-form-{size}', 'uk-form-width-{width}'],
            ],
            $attrs
        );

        return new HtmlElement('input', $attrs);
    }

    public function customAttributes(HtmlElement $el, string $attrs)
    {
        foreach (explode("\n", $attrs) as $attribute) {
            [$name, $value] = array_pad(explode('=', $attribute, 2), 2, '');

            $name = trim($name);

            if ($name && !in_array($name, ['id', 'class'])) {
                $el->attrs[$name] = $value
                    ? preg_replace('/^([\'"])(.*)(\1)/', '$2', $value)
                    : true;
            }
        }
    }
}
