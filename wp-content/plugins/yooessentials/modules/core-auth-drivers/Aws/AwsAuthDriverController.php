<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Auth\Driver\Aws;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Vendor\AsyncAws\Core\Sts\StsClient;

class AwsAuthDriverController
{
    /**
     * @var string
     */
    public const PRE_SAVE_ENDPOINT = 'yooessentials/auth/aws';

    public function presave(Request $request, Response $response)
    {
        $form = $request->getParam('auth', []);
        $accessKeyId = $form['access_key_id'] ?? null;
        $accessKeySecret = $form['access_key_secret'] ?? null;

        if (!$accessKeyId) {
            return $response->withJson('Access Key ID is required.', 400);
        }

        if (!$accessKeySecret) {
            return $response->withJson('Access Key Secret is required.', 400);
        }

        try {
            (new StsClient(['accessKeyId' => $accessKeyId, 'accessKeySecret' => $accessKeySecret]))->getCallerIdentity([]);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson($form, 200);
    }
}
