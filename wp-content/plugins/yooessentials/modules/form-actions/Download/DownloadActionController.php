<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Download;

use YOOtheme\File;
use YOOtheme\Http\Request;
use YOOtheme\Http\Response;
use ZOOlanders\YOOessentials\BinaryFileResponse;
use ZOOlanders\YOOessentials\Encryption\Encrypter;

class DownloadActionController
{
    public const DOWNLOAD_URL = '/yooessentials/form/action/download';

    public function download(Request $request, Response $response, Encrypter $encrypter)
    {
        $file = $encrypter->decrypt($request->getParam('file', ''));

        if (!$file || !File::exists($file)) {
            throw new \RuntimeException('Trying to download an invalid or inexistent file.');
        }

        $response = (new BinaryFileResponse())->withFile($file);

        return $response->setContentDisposition(BinaryFileResponse::DISPOSITION_ATTACHMENT);
    }
}
