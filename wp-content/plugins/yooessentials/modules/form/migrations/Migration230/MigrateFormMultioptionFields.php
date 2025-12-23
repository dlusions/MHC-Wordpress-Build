<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Migration\Migration230;

use ZOOlanders\YOOessentials\Migration\Migration230Helper;
use ZOOlanders\YOOessentials\Migration\AbstractMigration;
use ZOOlanders\YOOessentials\Migration\MigrationInterface;

class MigrateFormMultioptionFields extends AbstractMigration implements MigrationInterface
{
    public const VERSION = '2.3.0';

    public function migrateNode(object $node, array $params): void
    {
        Migration230Helper::iterateNodeSources($node, function ($source) {
            $isFormQuery = fn ($src) => ($src->query->name ?? '') === 'yooessentials_form_query';
            $isMultiOptionField = fn ($src) => isset($src->name, $src->implode);

            if ($isFormQuery($source) && $isMultiOptionField($source)) {
                $source->query->field = clone (object) [
                    'name' => $source->name
                ];

                $source->name = 'value';
            }
        });
    }
}
