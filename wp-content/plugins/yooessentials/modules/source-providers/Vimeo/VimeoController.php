<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Vimeo;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class VimeoController
{
    /** @var string */
    public const PRESAVE_ENDPOINT = 'yooessentials/source/vimeo';

    public function presave(Request $request, Response $response)
    {
        $form = $request->getParam('form') ?? [];
        $account = $form['account'] ?? null;

        if (!$account) {
            return $response->withJson('Account must be specified.', 400);
        }

        return $response->withJson(200);
    }
}
