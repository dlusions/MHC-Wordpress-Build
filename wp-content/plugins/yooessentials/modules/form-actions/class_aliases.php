<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use ZOOlanders\YOOessentials\Form\Action\Csv\CsvUpsertAction;
use ZOOlanders\YOOessentials\Form\Action\Download\DownloadAction;
use ZOOlanders\YOOessentials\Form\Action\Email\EmailAction;
use ZOOlanders\YOOessentials\Form\Action\GoogleSheets\GoogleSheetsUpsertAction;
use ZOOlanders\YOOessentials\Form\Action\Message\MessageAction;
use ZOOlanders\YOOessentials\Form\Action\Redirect\RedirectAction;

class_alias(
    DownloadAction::class,
    '\\ZOOlanders\\YOOessentials\\Form\\Action\\DownloadAction'
);
class_alias(
    EmailAction::class,
    '\\ZOOlanders\\YOOessentials\\Form\\Action\\EmailAction'
);
class_alias(
    MessageAction::class,
    '\\ZOOlanders\\YOOessentials\\Form\\Action\\MessageAction'
);
class_alias(
    RedirectAction::class,
    '\\ZOOlanders\\YOOessentials\\Form\\Action\\RedirectAction'
);
class_alias(
    CsvUpsertAction::class,
    '\\ZOOlanders\\YOOessentials\\Form\\Action\\SaveCsvAction'
);
class_alias(
    CsvUpsertAction::class,
    '\\ZOOlanders\\YOOessentials\\Form\\Action\\SaveCsv\\SaveCsvAction'
);
class_alias(
    GoogleSheetsUpsertAction::class,
    '\\ZOOlanders\\YOOessentials\\Form\\Action\\SaveGoogleSheetAction'
);
class_alias(
    GoogleSheetsUpsertAction::class,
    '\\ZOOlanders\\YOOessentials\\Form\\Action\\SaveGoogleSheet\\SaveGoogleSheetAction'
);
