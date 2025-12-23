<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Facebook;

/**
 * @see https://developers.facebook.com/docs/graph-api/reference
 */
class FacebookApi extends FacebookBaseApi implements FacebookApiInterface
{
    const TYPE_PROFILE = 'profile';
    const TYPE_UPLOADED = 'uploaded';

    const DEFAULT_LIMIT = 20;
    const DEFAULT_POST_TYPE = 'published_posts';

    // prettier-ignore
    const PAGE_FIELDS = ['id', 'name', 'username', 'link', 'website', 'about', 'category', 'whatsapp_number', 'description', 'description_html', 'general_info', 'overall_star_rating', 'rating_count', 'followers_count', 'talking_about_count', 'birthday', 'personal_interests', 'affiliation'];

    // prettier-ignore
    const POST_FIELDS = ['id', 'parent_id', 'created_time', 'updated_time', 'from{name}', 'is_expired', 'is_hidden', 'is_popular', 'is_published', 'full_picture', 'reactions.summary(true)', 'comments.summary(true)', 'message', 'message_tags{name}', 'permalink_url', 'likes.summary(true)', 'shares', 'properties', 'attachments'];

    // prettier-ignore
    const EVENT_FIELDS = ['id', 'type', 'name', 'description', 'owner', 'cover', 'place', 'category', 'start_time', 'end_time', 'created_time', 'updated_time', 'scheduled_publish_time', 'timezone', 'interested_count', 'maybe_count', 'attending_count', 'declined_count', 'noreply_count', 'is_canceled', 'is_online', 'online_event_third_party_url', 'online_event_format', 'ticket_uri', 'ticketing_privacy_uri', 'ticketing_terms_uri'];

    // prettier-ignore
    const REVIEW_FIELDS = ['created_time', 'has_rating', 'has_review', 'rating', 'recommendation_type', 'review_text', 'reviewer{id,first_name,last_name,middle_name,name,name_format,picture,short_name}'];

    // prettier-ignore
    const PHOTO_FIELDS = ['id', 'name', 'album', 'width', 'from', 'height', 'link', 'images', 'event', 'name_tags{name}', 'place', 'alt_text', 'alt_text_custom', 'backdated_time', 'created_time', 'target', 'updated_time', 'comments.summary(true)', 'likes.summary(true)', 'shares'];

    /**
     * @see https://developers.facebook.com/docs/graph-api/reference/page
     */
    public function page(string $pageId): array
    {
        return $this->get($pageId, [
            'fields' => implode(',', self::PAGE_FIELDS),
        ]);
    }

    /**
     * @see https://developers.facebook.com/docs/graph-api/reference/page/posts
     * @param posts|published_posts|scheduled_posts|visitor_posts|ads_posts|feed
     */
    public function posts(string $userOrPageId, array $args = []): array
    {
        $this->withAccessToken($this->getPageAccessToken($userOrPageId));

        $type = $args['type'] ?? self::DEFAULT_POST_TYPE;

        $result = $this->get("{$userOrPageId}/{$type}", [
            'fields' => implode(',', self::POST_FIELDS),
            'limit' => $args['limit'] ?? self::DEFAULT_LIMIT,
        ]);

        return $result['data'] ?? [];
    }

    /**
     * @see https://developers.facebook.com/docs/graph-api/reference/event
     */
    public function events(string $userOrPageId, array $args = []): array
    {
        $this->withAccessToken($this->getPageAccessToken($userOrPageId));

        $result = $this->get("{$userOrPageId}/events", $args + [
            'fields' => implode(',', self::EVENT_FIELDS),
            'limit' => $args['limit'] ?? self::DEFAULT_LIMIT,
        ]);

        return $result['data'] ?? [];
    }

    /**
     * @see https://developers.facebook.com/docs/graph-api/reference/page/ratings
     */
    public function reviews(string $userOrPageId, array $filters = []): array
    {
        $this->withAccessToken($this->getPageAccessToken($userOrPageId));

        $result = $this->get("{$userOrPageId}/ratings", [
            'fields' => implode(',', self::REVIEW_FIELDS),
            'limit' => $filters['limit'] ?? self::DEFAULT_LIMIT,
        ]);

        return $result['data'] ?? [];
    }

    /**
     * @see https://developers.facebook.com/docs/graph-api/reference/page/photos
     */
    public function photos(string $userOrPageId, array $filters = []): array
    {
        $this->withAccessToken($this->getPageAccessToken($userOrPageId));

        $result = $this->get("{$userOrPageId}/photos", [
            'fields' => implode(',', self::PHOTO_FIELDS),
            'limit' => $filters['limit'] ?? self::DEFAULT_LIMIT,
            'type' => $filters['type'] ?? self::TYPE_UPLOADED,
        ]);

        return $result['data'] ?? [];
    }

    public function getPageAccessToken(string $pageId): ?string
    {
        return $this->get($pageId, ['fields' => 'access_token'])['access_token'] ?? null;
    }
}
