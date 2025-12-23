<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Mailchimp;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;

class MailchimpSourceController
{
    use LoadsSourceFromArgs, HasApiRequest;

    public const PRESAVE_ENDPOINT = 'yooessentials/source/mailchimp';
    public const AUDIENCE_LISTS_ENDPOINT = 'yooessentials/source/mailchimp/lists';
    public const INTEREST_CATEGORIES_ENDPOINT = 'yooessentials/source/mailchimp/interest-categories';

    public function presave(Request $request, Response $response)
    {
        $form = $request('form');
        $auth = $form['auth'] ?? null;
        $list = $form['list'] ?? null;

        try {
            if (!$auth) {
                throw new \Exception('Authentication must be specified.');
            }

            if (!$list) {
                throw new \Exception('An Audience must be specified.');
            }

            return $response->withJson(200);
        } catch (\Exception $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }

    public function lists(Request $request, Response $response)
    {
        $form = $request->getParam('form');
        $auth = $form['auth'] ?? '';

        if (!$auth) {
            return $response->withJson('Auth argument is missing.', 400);
        }

        try {
            $lists = self::api($auth)->getAllLists();
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        $items = array_map(fn ($list) => [
            'text' => $list['name'],
            'value' => $list['id'],
            'meta' => $list['id'],
        ], $lists);

        return $response->withJson($items);
    }

    public function interestCategories(Request $request, Response $response)
    {
        $sourceId = $request->getParam('source_id') ?? [];

        $source = self::loadSource([
            'source_id' => $sourceId
        ], MailchimpSource::class);

        $api = self::api($source->auth);

        try {
            $categories = $api->listInterestCategories($source->list, [
                'include_fields' => 'id, title'
            ])['categories'] ?? [];

            $result = array_map(fn ($cat) => [
                'text' => $cat['title'],
                'value' => $cat['id']
            ], $categories);

            return $response->withJson($result, 200);
        } catch (\Exception $e) {
            return $response->withJson($e->getMessage(), 400);
        }
    }
}
