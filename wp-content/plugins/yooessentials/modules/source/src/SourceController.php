<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source;

use YOOtheme\Builder\Source;
use YOOtheme\Http\Response;

class SourceController
{
    public const REBUILD_SCHEMA_URL = 'yooessentials/source/rebuild-schema';

    public function rebuildSchema(Response $response, Source $source)
    {
        // extract schema
        $result = $source->queryIntrospection()->toArray();
        $schema = $result['data']['__schema'] ?? $result;

        return $response->withJson(['schema' => $schema]);
    }
}
