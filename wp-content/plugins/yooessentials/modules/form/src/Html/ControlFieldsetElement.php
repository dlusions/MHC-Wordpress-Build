<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Html;

use YOOtheme\View\HtmlElement;

class ControlFieldsetElement
{
    public ?string $id;

    public ?string $name;

    public ?string $label;

    public ?bool $required;

    /**
     * Constructor.
     */
    public function __construct(?string $name = null, ?string $label = null, ?bool $required = false, ?string $id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->required = $required;
    }

    /**
     * Renders element shortcut.
     *
     * @see render()
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Render element shortcut.
     *
     * @see render()
     */
    public function __invoke(array $params = [], array $attrs = []): string
    {
        return $this->render($params, $attrs);
    }

    /**
     * Renders the element tag.
     */
    public function render(array $params = [], array $attrs = []): string
    {
        $html = [];

        $attrs = HtmlElement::attrs(
            array_merge(
                [
                    'data-yooessentials-form-field' => $this->name,
                ],
                $attrs
            ),
            $params
        );

        $for = $this->id ?? $this->name;

        $html[] = "<fieldset{$attrs} style=\"border-width: 0; margin: 0; padding: 0;\">";

        if ($this->label) {
            $html[] = '<legend class="uk-form-label">';
            $html[] = $this->label;

            if ($this->required) {
                $html[] = ' <span>*</span>';
            }

            $html[] = '</legend>';
        }

        $html[] = '<div class="uk-form-controls">';

        return implode('', $html);
    }

    /**
     * Renders element closing tag.
     */
    public function end(): string
    {
        $errors = new HtmlElement(
            'div',
            [
                'class' => ['uk-text-danger', 'uk-text-small'],
                'data-yooessentials-form-field-errors' => true,
            ],
            ''
        );

        return "</div>{$errors()}</fieldset>";
    }
}
