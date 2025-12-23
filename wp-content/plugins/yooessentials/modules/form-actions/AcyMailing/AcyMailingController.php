<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\AcyMailing;

use function YOOtheme\app;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Api\AcyMailing\AcyMailingApiInterface;

class AcyMailingController
{
    public const LISTS_ENDPOINT = 'yooessentials/form/action/acymailing/lists';
    public const CUSTOM_FIELDS_ENDPOINT = 'yooessentials/form/action/acymailing/custom-fields';

    public function listsField(Response $response): Response
    {
        try {
            $acyMailing = app(AcyMailingApiInterface::class);

            $lists = $acyMailing->getLists(['limit' => 999]);

            $items = array_values(array_map(fn ($list) => [
                'value' => $list->id,
                'text' => $list->name,
            ], $lists));

            $fields = [
                'lists' => [
                    'type' => 'select',
                    'label' => 'Lists',
                    'description' => 'The lists where to perform the action on.',
                    'options' => $items,
                    'source' => true,
                    'attrs' => [
                        'multiple' => true,
                    ],
                ],
            ];

            return $response->withJson($fields);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function customFields(Response $response): Response
    {
        try {
            $acyMailing = app(AcyMailingApiInterface::class);

            $fields = array_map(fn ($field) => [
                'id' => $field->id,
                'title' => $field->name . ($field->required ? ' *' : ''),
                'type' => $field->type,
                'meta' => ['type' => $field->type]
            ], array_values($acyMailing->getCustomFields()));

            return $response->withJson($fields);

        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }
}
