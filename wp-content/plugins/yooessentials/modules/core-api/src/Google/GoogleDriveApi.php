<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Api\Google;

class GoogleDriveApi extends GoogleApi implements GoogleDriveApiInterface
{
    protected string $apiEndpoint = 'https://www.googleapis.com/drive/v3';

    public function files(array $args = []): array
    {
        return $this->get('files', array_merge($args, ['pageSize' => 50, 'includeItemsFromAllDrives' => 'true', 'supportsAllDrives' => 'true']));
    }
}
