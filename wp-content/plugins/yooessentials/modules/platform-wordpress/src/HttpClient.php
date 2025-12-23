<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Wordpress;

use ZOOlanders\YOOessentials\HttpClientInterface;

class HttpClient extends \YOOtheme\Wordpress\HttpClient implements HttpClientInterface
{
    public function patch($url, $data = null, $options = [])
    {
        $options['method'] = 'PATCH';

        if ($data) {
            $options['body'] = $data;
        }

        return $this->makeRequest($url, $options);
    }
}
