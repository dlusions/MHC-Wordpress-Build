<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Mailchimp;

interface MailchimpApiInterface
{
    public function getAccountMetadata(): array;

    public function getList(string $listId, array $args = []): array;

    public function getAllLists(array $args = []): array;

    public function getListMember(string $listId, string $subscriberHash, array $args = []): array;

    public function getListMembers(string $listId, array $args = []): array;

    public function addListMember(string $listId, array $member = [], bool $skipMergeFieldsValidation = false): array;

    public function addOrUpdateListMember(string $listId, string $memberId, array $member = [], $skipMergeFieldsValidation = false): array;

    public function archiveListMember(string $listId, string $memberId): array;

    public function deleteListMember(string $listId, string $memberId): array;

    public function getMergeFields(string $listId): array;

    public function listInterestCategories(string $listId, array $args = []): array;

    public function listInterestsInCategory(string $listId, string $categoryId, array $args = []): array;

    public function getOauthMetadata(string $accessToken): array;

    public function withDatacenter(string $dataCenter): self;
}
