<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Mailchimp;

use ZOOlanders\YOOessentials\Form\Action\StandardAction;
use ZOOlanders\YOOessentials\Form\Action\ActionRuntimeException;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;
use ZOOlanders\YOOessentials\Util;

class MailchimpActionUpsertMember extends StandardAction
{
    use HasApiRequest;
    use HasValidation;

    public const NAME = 'mailchimp-upsert-member';

    public function __invoke(FormSubmissionResponse $response, callable $next): FormSubmissionResponse
    {
        $config = (object) $this->getConfig();
        $member = (object) Util\Prop::filterByPrefix((array) $config, 'member_');

        try {
            self::validateConfig($config);
            self::prepareMember($member);

            $api = self::api($config->auth);
            $memberId = md5($member->email_address);
            $update = $config->update_if_exists ?? false;
            $skipMergeValidation = $config->skip_merge_validation ?? false;

            if ($update) {
                $api->addOrUpdateListMember($config->list, $memberId, (array) $member, $skipMergeValidation);
            } else {
                $api->addListMember($config->list, (array) $member, $skipMergeValidation);
            }
        } catch (\Throwable $e) {
            $error = self::mapError($e->getMessage(), $config) ?: $e->getMessage();

            throw ActionRuntimeException::create($this, $error, $e);
        }

        return $next(
            $response->withDataLog([
                self::NAME => $config,
            ])
        );
    }

    protected static function prepareMember(object &$member)
    {
        // Location
        if (is_string($member->location ?? null)) {
            $value = Util\Arr::explodeList($member->location);

            if (count($value) === 2) {
                list($latitude, $longitude) = $value;
                $member->location = ['latitude' => $latitude, 'longitude' => $longitude];
            } else {
                unset($member->location);
            }
        }

        // Tags
        if (is_string($member->tags ?? null)) {
            $member->tags = Util\Arr::explodeList($member->tags);
        }

        // Interests
        $member->interests = array_filter((array) $member->interests ?? []);
        $member->interests = (object) array_fill_keys(array_values($member->interests), true);

        // Marketing Permissions
        $member->marketing_permissions = Util\Prop::filterByPrefix((array) $member, 'marketing_permissions_');
        $member->marketing_permissions = array_map(fn ($value, $id) => [
            'marketing_permission_id' => $id,
            'enabled' => $value
        ], array_values($member->marketing_permissions), array_keys($member->marketing_permissions));

        // Merge Fields
        $member->merge_fields = (object) array_reduce($member->merge_fields ?? [], function ($carry, $field) {
            $field = (object) $field;
            $props = $field->props ?? [];
            $status = $props['status'] ?? null;

            if ($status === 'disabled') {
                return $carry;
            }

            unset($props['status']);

            $carry[$field->id] = count($props) === 1 ? array_pop($props) : $props;

            return $carry;
        });

        // Cleanup
        $member = (object) array_filter((array) $member, fn ($key) => strpos($key, 'interests_') !== 0 && strpos($key, 'marketing_permissions_') !== 0, ARRAY_FILTER_USE_KEY);
    }
}
