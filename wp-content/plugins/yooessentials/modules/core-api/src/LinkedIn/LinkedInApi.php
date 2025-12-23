<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\LinkedIn;

use YOOtheme\Arr;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\Api\AbstractApi;
use ZOOlanders\YOOessentials\Api\WithAuthentication;
use ZOOlanders\YOOessentials\Api\HasAuthentication;

// https://learn.microsoft.com/en-us/linkedin/shared/integrations/people/profile-api
// https://learn.microsoft.com/en-us/linkedin/marketing/community-management/shares/posts-api
class LinkedInApi extends AbstractApi implements LinkedInApiInterface, HasAuthentication
{
    use WithAuthentication;

    protected const API_VERSION = '202510';

    protected string $apiEndpoint = 'https://api.linkedin.com/rest';

    public function posts(array $args = []): array
    {
        $result = $this->get('posts', array_merge(Arr::pick($args, ['q', 'author', 'start', 'count', 'sortBy', 'fields'])));

        return $result['elements'] ?? [];
    }

    public function post(string $urn, array $args = []): array
    {
        $urn = urlencode($urn);

        return $this->get("posts/$urn");
    }

    /**
     * https://learn.microsoft.com/en-us/linkedin/marketing/community-management/shares/posts-api?view=li-lms-2025-05&source=recommendations&tabs=http#find-posts-by-authors
     * @param $urn {encoded person urn or organization urn like urn%3Ali%3Aperson%3A5abc_dEfgH or urn%3Ali%3Aorganization%3A2414183}
     */
    public function postsByAuthor(string $urn, array $args = []): array
    {
        $posts = $this->posts(array_merge($args, [
            'author' => $urn,
            'q' => 'author'
        ]));

        if (isset($posts[0]['author'])) {
            $organization = $this->organization($posts[0]['author']);
        }

        foreach ($posts as &$post) {
            $post['author'] = $organization;

            // $post['contentMedia'] = null;
            $post['contentImages'] = [];
            $post['contentMedia']['mediaType'] = null;

            // TODO when API is fixed
            // $post['socialActions'] = $this->socialActions($post['id']);
            // if (isset($post['content']['reference'])) {
            //     $id = $post['content']['reference']['id'];
            // }

            if (isset($post['content']['media'])) {
                $id = $post['content']['media']['id'];

                if (strpos($id, 'urn:li:image:') !== false) {
                    $media = $this->image($id);
                    $mediaType = 'image';
                } elseif (strpos($id, 'urn:li:video:') !== false) {
                    $media = $this->video($id);
                    $mediaType = 'video';
                }

                $post['contentMedia'] = array_merge($post['content']['media'], $media);
                $post['contentMedia']['mediaType'] = $mediaType;

                unset($post['content']['media']);
            }

            if (isset($post['content']['multiImage']['images'])) {
                foreach ($post['content']['multiImage']['images'] as $key => $image) {
                    $post['contentImages'][] = array_merge($image, $this->image($image['id']));
                }

                unset($post['content']['multiImage']);
            }
        }

        return $posts;
    }

    // https://learn.microsoft.com/en-us/linkedin/marketing/community-management/shares/network-update-social-actions?view=li-lms-2024-10&tabs=http#retrieve-social-actions
    public function socialActions(string $urn, array $args = []): array
    {
        $urn = urlencode($urn);
        $result = $this->get("socialActions/$urn", $args);

        return $result['elements'] ?? [];
    }

    public function organizations(): array
    {
        $result = $this->get('organizationAcls?q=roleAssignee&role=ADMINISTRATOR&state=APPROVED');

        return $result['elements'] ?? [];
    }

    public function organization(string $urn, array $args = []): array
    {
        $fields = [
            'id',
            'localizedName',
            'localizedWebsite',
            'localizedDescription',
            'logoV2',
        ];

        $id = str_replace('urn:li:organization:', '', $urn);
        $org = $this->get("organizations/$id", array_merge($args, [
            'fields' => $fields,
        ]));

        if (isset($org['logoV2']['original'])) {
            $org['logoV2']['original'] = $this->image($org['logoV2']['original']);
        }

        return $org ?? [];
    }

    public function image(string $urn): array
    {
        $urn = str_replace('digitalmediaAsset', 'image', $urn);
        $urn = urlencode($urn);

        return $this->get("images/$urn");
    }

    public function video(string $urn): array
    {
        $urn = urlencode($urn);

        return $this->get("videos/$urn");
    }

    public function article(string $urn): array
    {
        $urn = str_replace('urn:li:linkedInArticle:', '', $urn);

        // return $this->get("originalArticles/$urn");
        return $this->get("articles/$urn");
    }

    public function people(string $id): array
    {
        return $this->get("people/(id:$id)");
    }

    public function refreshAccessToken(string $clientId, string $clientSecret, string $refreshToken): array
    {
        $response = $this->client->post(
            'https://www.linkedin.com/oauth/v2/accessToken',
            [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $refreshToken,
                'grant_type' => 'refresh_token',
            ],
            [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ]
            ]
        );

        return $this->processResponse($response);
    }

    protected function getHeaders(): array
    {
        $headers = parent::getHeaders();

        $headers += [
            'X-Restli-Protocol-Version' => '2.0.0',
            'LinkedIn-Version' => self::API_VERSION,
        ];

        $headers += $this->getAuthorizationHeader();

        return $headers;
    }

    protected function buildQuery($url, array $args): string
    {
        $fields = $args['fields'] ?? [];
        unset($args['fields']);

        $query = parse_url($url)['query'] ?? '';
        $query = ($query ? '&' : '?') . http_build_query($args, '', '&');

        $query = $fields ? $query . '&fields=' . implode(',', $fields) : $query;

        return $query;
    }

    protected function processResponse(Response $response): array
    {
        $result = json_decode($response->getBody(), true);
        $success =
            $response->getStatusCode() >= 200 && $response->getStatusCode() <= 299 && ($result['message'] ?? '') !== 'error';

        if (!$success) {
            $code = $result['error_code'] ?? ($response->getStatusCode() ?? 400);
            $message = $result['message'] ?? ($response->getReasonPhrase() ?? $result['code'] ?? 'Unknown Error');

            throw new \Exception($message, $code);
        }

        return $result;
    }
}
