<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Mailchimp;

use YOOtheme\Http\Request;
use YOOtheme\Http\Response;

class MailchimpActionController
{
    use HasApiRequest;

    public const LISTS_ENDPOINT = 'yooessentials/form/action/mailchimp/lists';
    public const MERGE_FIELDS_ENDPOINT = 'yooessentials/form/action/mailchimp/merge-fields';
    public const INTERESTS_ENDPOINT = 'yooessentials/form/action/mailchimp/interests';
    public const MARKETING_PERMISSIONS_ENDPOINT = 'yooessentials/form/action/mailchimp/marketing-permissions';

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

    public function mergeFields(Request $request, Response $response)
    {
        $form = $request->getParam('form');
        $list = $form['list'] ?? null;
        $auth = $form['auth'] ?? '';

        if (!$auth) {
            return $response->withJson('Auth argument is missing.', 400);
        }

        if (!$list) {
            return $response->withJson('List argument is missing.', 400);
        }

        try {
            $result = self::api($auth)->getMergeFields($list);

            $fields = array_map(fn ($field) => [
                'id' => $field['tag'],
                'title' => $field['name'] . ($field['required'] ? ' *' : ''),
                'type' => $field['type'],
                'meta' => ['tag' => $field['tag'], 'type' => $field['type']],
                'data' => [
                    'tag' => $field['tag'],
                    'title' => $field['name'] . ($field['required'] ? ' *' : '')
                ]
            ], $result['merge_fields'] ?? []);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson($fields);
    }

    public function interests(Request $request, Response $response)
    {
        $form = $request->getParam('form');
        $list = $form['list'] ?? null;
        $auth = $form['auth'] ?? '';
        $api = self::api($auth);

        try {
            $categories = $api->listInterestCategories($list, [
                'exclude_fields' => 'list_id, total_items, _links'
            ]);

            $options = [];

            foreach ($categories['categories'] ?? [] as $category) {
                $interests = $api->listInterestsInCategory($list, $category['id'] ?? '', [
                    'fields' => 'interests'
                ]);

                $options[] = [
                    'label' => $category['title'],
                    'options' => array_map(fn ($interest) => [
                        'text' => $interest['name'],
                        'value' => $interest['id']
                    ], $interests)
                ];
            }

            $fields[] = [
                'name' => 'member_interests',
                'type' => 'select',
                'description' => 'The list of interests for this category, press <code>CTRL</code> or <code>CMD</code> to select multiple interests. If mapping dynamic values, use the ids as reference.',
                'source' => true,
                'options' => $options,
                'attrs' => [
                    'class' => 'uk-height-medium',
                    'multiple' => true,
                ],
            ];
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson($fields);
    }

    public function marketingPermissions(Request $request, Response $response)
    {
        $form = $request->getParam('form');
        $list = $form['list'] ?? null;
        $auth = $form['auth'] ?? '';

        try {
            // Get marketing permissions from the first member found
            $result = self::api($auth)->getListMembers($list, [
                'fields' => 'members.marketing_permissions',
                'count' => 1
            ]);

            $members = $result['members'] ?? [];

            if (!count($members)) {
                throw new \Exception('No members found. The Audience needs to have at least one member.');
            }

            $fields = array_map(fn ($field) => [
                'name' => "member_marketing_permissions_{$field['marketing_permission_id']}",
                'type' => 'checkbox',
                'label' => $field['text'],
                'text' => 'Granted',
                'source' => true
            ], $members[0]['marketing_permissions'] ?? []);
        } catch (\Throwable $e) {
            return $response->withJson($e->getMessage(), 400);
        }

        return $response->withJson($fields);
    }
}
