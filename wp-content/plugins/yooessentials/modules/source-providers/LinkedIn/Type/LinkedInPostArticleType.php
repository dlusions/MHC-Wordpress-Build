<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\LinkedIn\Type;

use ZOOlanders\YOOessentials\Source\GraphQL\GenericType;

class LinkedInPostArticleType extends GenericType
{
    public const NAME = 'LinkedinPostArticle';
    public const LABEL = 'Article';

    public function config(): array
    {
        return [
            'fields' => [
                'id' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => 'ID',
                        'description' => 'Unique identifier of the content',
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
