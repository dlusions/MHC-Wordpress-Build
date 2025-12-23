<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials;

use YOOtheme\File;
use YOOtheme\Http\Response;

class CoreController
{
    public const GET_CHANGELOG_URL = 'yooessentials/changelog';
    public const GET_TABLE_COLUMNS_ENDPOINT = 'yooessentials/core/table/columns';
    public const DOWNLOAD_DEBUG_DATA_URL = 'yooessentials/debug/data';

    public function getChangelog(Response $response): Response
    {
        return $response->withFile(File::get('~yooessentials/CHANGELOG.md'));
    }
}
