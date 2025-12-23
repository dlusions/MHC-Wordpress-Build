<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Bluesky\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class BlueskyFeedType extends GenericType
{
    public const NAME = 'BlueskyFeed';
    public const LABEL = 'Feed';

    public function config(): array
    {
        return [
            'fields' => [
                'post' => [
                    'type' => BlueskyPostType::NAME,
                    'metadata' => [
                        'label' => 'Post',
                        'description' => 'The feed post.',
                    ],
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => $this->label(),
            ],
        ];
    }
}
