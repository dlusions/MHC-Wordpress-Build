<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Xml;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Auth\AuthManager;

class XmlController
{
    public const PRESAVE_ENDPOINT = 'yooessentials/source/xml';
    public const METADATA_ENDPOINT = 'yooessentials/source/xml/metadata';

    public function metadata(Request $request, Response $response, XmlService $xml, AuthManager $authManager)
    {
        $config = $request->getParam('form');

        $source = XmlSourceFile::fromConfig($config);

        try {
            $objectType = $source->objectType();
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson($objectType->fieldsMetadata());
    }

    public function presave(Request $request, Response $response, XmlService $xml, AuthManager $authManager)
    {
        $form = $request->getParam('form');
        $type = $form['provider'] ?? null;
        $file = $form['file'] ?? null;

        if ($type === XmlSourceFile::NAME && !$file) {
            return $response->withJson('Missing file.', 400);
        }

        if ($type == XmlSourceFile::NAME) {
            try {
                $xml->loadFromFile($file);

                return $response->withJson(200);
            } catch (\Throwable $e) {
                return $response->withJson($e->getMessage(), 400);
            }
        }

        $url = $form['url'] ?? null;
        $hasAuth = $form['requires_auth'] ?? null;
        $auth = $form['auth'] ?? null;

        if (!$url) {
            return $response->withJson('Missing Url.', 400);
        }

        if ($hasAuth && !$auth) {
            return $response->withJson('Missing Auth details', 400);
        }

        $auth = $hasAuth ? $authManager->auth($auth) : null;

        try {
            $xml->loadFromUrl($url, $auth);

            return $response->withJson(200);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }
}
