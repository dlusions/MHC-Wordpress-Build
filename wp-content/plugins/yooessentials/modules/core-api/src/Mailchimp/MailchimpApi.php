<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Mailchimp;

use YOOtheme\Arr;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Api\AbstractApi;
use ZOOlanders\YOOessentials\Api\WithAuthentication;
use ZOOlanders\YOOessentials\Api\HasAuthentication;

// https://mailchimp.com/developer/marketing/api/
class MailchimpApi extends AbstractApi implements MailchimpApiInterface, HasAuthentication
{
    use WithAuthentication;

    protected string $apiEndpoint = 'https://{dc}.api.mailchimp.com/3.0';

    // https://mailchimp.com/developer/marketing/api/root/list-api-root-resources/
    public function getAccountMetadata(): array
    {
        return $this->get('/');
    }

    // https://mailchimp.com/developer/marketing/api/lists/get-list-info/
    public function getList(string $listId, array $args = []): array
    {
        return $this->get("lists/$listId", $args);
    }

    // https://mailchimp.com/developer/marketing/api/list-members/get-member-info/
    public function getListMember(string $listId, string $subscriberHash, array $args = []): array
    {
        return $this->get("lists/{$listId}/members/{$subscriberHash}", $args);
    }

    public function getListMembers(string $listId, array $args = []): array
    {
        return $this->get("/lists/{$listId}/members", $args);
    }

    // https://mailchimp.com/developer/marketing/api/list-members/add-member-to-list/
    public function addListMember(string $listId, array $member = [], bool $skipMergeFieldsValidation = false): array
    {
        $url = "lists/$listId/members";

        if ($skipMergeFieldsValidation) {
            $url .= '?skip_merge_validation=true';
        }

        return $this->post($url, $member);
    }

    // https://mailchimp.com/developer/marketing/api/list-members/add-or-update-list-member/
    public function addOrUpdateListMember(string $listId, string $memberId, array $member = [], $skipMergeFieldsValidation = false): array
    {
        $url = "lists/$listId/members/$memberId";

        if ($skipMergeFieldsValidation) {
            $url .= '?skip_merge_validation=true';
        }

        return $this->put($url, $member);
    }

    // https://mailchimp.com/developer/marketing/api/list-members/archive-list-member/
    public function archiveListMember(string $listId, string $memberId): array
    {
        return $this->delete("lists/$listId/members/$memberId");
    }

    // https://mailchimp.com/developer/marketing/api/list-members/delete-list-member/
    public function deleteListMember(string $listId, string $memberId): array
    {
        return $this->post("lists/$listId/members/$memberId/actions/delete-permanent");
    }

    // https://mailchimp.com/developer/marketing/api/lists/
    public function getAllLists(array $args = []): array
    {
        $result = $this->get('lists', array_merge([
            'count' => 100,
            'include_total_contacts' => true,
        ], $args));

        return $result['lists'] ?? [];
    }

    public function getMergeFields(string $listId): array
    {
        return $this->get("lists/{$listId}/merge-fields");
    }

    public function listInterestCategories(string $listId, array $args = []): array
    {
        return $this->get("lists/{$listId}/interest-categories", $args);
    }

    public function listInterestsInCategory(string $listId, string $categoryId, array $args = []): array
    {
        return $this->get(
            "lists/$listId/interest-categories/$categoryId/interests",
            Arr::pick($args, ['count', 'offset', 'fields', 'exclude_fields'])
        )['interests'] ?? [];
    }

    public function getOauthMetadata(string $accessToken): array
    {
        return $this->processResponse($this->client->get('https://login.mailchimp.com/oauth2/metadata', [
            'headers' => [
                'Authorization' => "OAuth {$accessToken}"
            ]
        ]));
    }

    public function withApiKey(string $key): self
    {
        list(, $dataCenter) = explode('-', $key);

        $this->apiKey = $key;

        return $this->withDatacenter($dataCenter);
    }

    public function withDatacenter(string $dataCenter): self
    {
        $this->apiEndpoint = str_replace('{dc}', $dataCenter, $this->apiEndpoint);

        return $this;
    }

    protected function getHeaders(): array
    {
        $headers = parent::getHeaders();
        $headers['User-Agent'] = 'YOOessentials/1.0.0';
        $headers['Authorization'] = $this->apiKey
            ? "apikey {$this->apiKey}"
            : "Bearer {$this->accessToken}";

        return $headers;
    }

    protected function processResponse(Response $response): array
    {
        $result = json_decode($response->getBody(), true);
        $success = $response->getStatusCode() >= 200 && $response->getStatusCode() <= 299 && ($result['message'] ?? '') !== 'error';

        if (!$success) {
            $code = $result['status'] ?? $response->getStatusCode() ?? 400;
            $error = isset($result['errors']) ? var_export($result['errors'], true) : $result['detail'] ?? '';
            $message = "{$result['title']}: {$error}" ?? $response->getReasonPhrase() ?? 'Unknown Error';

            throw new \RuntimeException($message, $code);
        }

        return (array) $result;
    }
}
