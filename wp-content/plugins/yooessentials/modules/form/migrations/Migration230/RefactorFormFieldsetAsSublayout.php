<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration230;

use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class RefactorFormFieldsetAsSublayout extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.3.0';

    public function type(): ?string
    {
        return 'yooessentials_form_fieldset';
    }

    public function migrateNode(object $node, array $params): void
    {
        if (
            isset($node->children[0]) &&
            ($node->children[0]->type ?? '') !== 'row'
        ) {
            $node->children = [
                (object) [
                    'type' => 'row',
                    'children' => [
                        (object) [
                            'type' => 'column',
                            'children' => $node->children
                        ]
                    ]
                ]
            ];
        }
    }
}
