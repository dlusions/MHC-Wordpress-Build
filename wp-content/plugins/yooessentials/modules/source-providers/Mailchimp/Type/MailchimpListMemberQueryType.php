<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Mailchimp\Type;

use ZOOlanders\YOOessentials\Api\Mailchimp\MailchimpApiInterface;
use ZOOlanders\YOOessentials\Source\Provider\Mailchimp\MailchimpSource;
use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Form\Action\Mailchimp\HasApiRequest;

class MailchimpListMemberQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData, HasApiRequest;

    private MailchimpListMemberType $memberType;

    public const NAME = 'listMember';
    public const LABEL = 'Member';
    public const DESCRIPTION = 'Audience member';

    public static function getCacheKey(): string
    {
        return 'mailchimp-list-member-query';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => MailchimpListMemberType::NAME,

                    'args' => [
                        'memberId' => [
                            'type' => 'String',
                        ],
                        'cache' => [
                            'type' => 'Int',
                        ],
                    ],

                    'metadata' => [
                        'group' => 'Essentials',
                        'label' => $this->label(),
                        'description' => $this->description(),
                        'fields' => [
                            'memberId' => [
                                'label' => 'Member ID',
                                'description' => 'The Audience member\'s email address or contact_id.',
                                'source' => true,
                            ],

                            'cache' => $this->cacheField(),
                        ],
                    ],

                    'extensions' => [
                        'call' => [
                            'func' => __CLASS__ . '::resolve',
                            'args' => [
                                'source_id' => $this->source->id(),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root, array $args): array
    {
        /** @var MailchimpSource */
        $source = self::loadSource($args, MailchimpSource::class);

        $memberId = $args['memberId'] ?? null;

        if (!$source || !$memberId) {
            return [];
        }

        $result = self::resolveFromCache($source, $args, function () use ($source, $memberId) {
            /** @var MailchimpApiInterface */
            $api = self::api($source->auth);

            return $api->getListMember($source->list, md5($memberId));
        });

        return $result;
    }
}
