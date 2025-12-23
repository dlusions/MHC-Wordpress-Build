<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Rss;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class RssController
{
    /** @var string */
    public const PRESAVE_ENDPOINT = 'yooessentials/source/rss';

    public function presave(Request $request, Response $response)
    {
        $form = $request->getParam('form');
        $url = $form['url'] ?? null;

        if (!$url) {
            return $response->withJson('Missing RSS feed Url.', 400);
        }

        try {
            (new RssSource($form))->rss();
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson(200);
    }
}
