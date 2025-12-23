<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\CloudflareStream;

use function YOOtheme\app;
use YOOtheme\Arr;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Api\Cloudflare\CloudflareStreamApi;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use ZOOlanders\YOOessentials\Source\SourceService;

class CloudflareStreamController
{
    public const PRE_SAVE_KEY_ENDPOINT = 'yooessentials/cloudflare/stream/presave-key';
    public const PRE_DELETE_KEY_ENDPOINT = 'yooessentials/cloudflare/stream/predelete-key';
    public const PRE_SAVE_SOURCE_ENDPOINT = 'yooessentials/cloudflare/stream/presave-source';

    public const GET_STREAM_ENDPOINT = 'yooessentials/cloudflare/stream';
    public const GET_STREAMS_ENDPOINT = 'yooessentials/cloudflare/streams';

    public function saveSource(Request $request, Response $response)
    {
        $form = $request->getParam('form') ?? [];

        try {
            $this->initApi($form)->streams();
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson(200);
    }

    public function createKey(Request $request, Response $response)
    {
        $form = $request->getParam('form') ?? [];
        $auth = $request->getParam('auth') ?? [];

        $id = $auth['id'] ?? false;
        $pem = $auth['pem'] ?? false;

        // skip if already has been created
        if ($id && $pem) {
            return $response->withJson(['id' => $id, 'pem' => $pem], 200);
        }

        try {
            $key = $this->initApi($form)->createStreamKey();
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson(Arr::pick($key, ['id', 'pem']), 200);
    }

    public function deleteKey(Request $request, Response $response)
    {
        $form = $request->getParam('form') ?? [];
        $id = $form['id'] ?? false;

        if (!$id) {
            return $response->withJson('Stream Signing Key ID must be specified.', 400);
        }

        try {
            $key = $this->initApi($form)->deleteStreamKey($id);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson($key, 200);
    }

    public function getStream(Request $request, Response $response)
    {
        $uid = $request->getParam('uid');
        $form = $request->getParam('form') ?? [];

        if (!$uid) {
            return $response->withJson('Stream ID must be specified.', 400);
        }

        try {
            $stream = $this->initApi($form)->stream($uid);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson($stream, 200);
    }

    public function getStreams(Request $request, Response $response, SourceService $sourceService)
    {
        $sourceId = $request->getParam('sourceId');

        if (!$sourceId) {
            return $response->withJson('Source ID must be specified.', 400);
        }

        try {
            $source = $sourceService->source($sourceId)->config();
            $streams = $this->initApi($source)->streams();
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson($streams, 200);
    }

    protected function initApi(array $form): CloudflareStreamApi
    {
        $authManager = app(AuthManager::class);

        $token = $form['token'] ?? null;
        $account = $form['account'] ?? null;

        if (!$token) {
            throw new \Exception('Token must be specified.');
        }

        if (!$account) {
            throw new \Exception('Account must be specified.');
        }

        $auth = $authManager->auth($token);

        if (!$auth) {
            throw new \Exception('Invalid Auth.');
        }

        if (!$auth->accessToken ?? false) {
            throw new \Exception('Token is invalid.');
        }

        return app(CloudflareStreamApi::class)
            ->withAccessToken($auth->accessToken)
            ->withAccount($account);
    }
}
