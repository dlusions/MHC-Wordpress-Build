<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Mailchimp\Type;

use ZOOlanders\YOOessentials\Source\Provider\Mailchimp\MailchimpSource;
use ZOOlanders\YOOessentials\Source\Provider\Mailchimp\HasApiRequest;
use ZOOlanders\YOOessentials\Source\GraphQL\AbstractQueryType;
use ZOOlanders\YOOessentials\Source\Resolver\CachesResolvedSourceData;
use ZOOlanders\YOOessentials\Source\Resolver\LoadsSourceFromArgs;
use ZOOlanders\YOOessentials\Source\Type\SourceInterface;

class MailchimpListQueryType extends AbstractQueryType
{
    use LoadsSourceFromArgs, CachesResolvedSourceData, HasApiRequest;

    private MailchimpListType $listType;

    public const NAME = 'list';
    public const LABEL = 'List Info';
    public const DESCRIPTION = 'Audience data';

    public static function getCacheKey(): string
    {
        return 'mailchimp-list-query';
    }

    public function config(): array
    {
        return [
            'fields' => [
                $this->name() => [
                    'type' => MailchimpListType::NAME,
                    'args' => [
                        'cache' => [
                            'type' => 'Int',
                        ],
                    ],
                    'metadata' => [
                        'group' => 'Essentials',
                        'label' => $this->label(),
                        'description' => $this->description(),
                        'fields' => [
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
        /** @var SourceInterface */
        $source = self::loadSource($args, MailchimpSource::class);

        if (!$source) {
            return [];
        }

        $result = self::resolveFromCache($source, $args, fn () => self::api($source->auth)->getList($source->list));

        return $result;
    }
}
