<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Controller;

use YOOtheme\Builder\Source;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class FormAdminController
{
    public const FIELDS_URL = 'yooessentials/form/schema';

    public function fields(Request $request, Response $response, Source $source)
    {
        $controls = $request->getParam('controls') ?? null;

        if (!$controls) {
            return $response->withJson('Missing Form Controls parameter.', 400);
        }

        $result = $source->queryIntrospection()->toArray();

        $schema = isset($result['data']) ? $result['data']['__schema'] : $result;

        return $response->withJson($schema, 200);
    }
}
