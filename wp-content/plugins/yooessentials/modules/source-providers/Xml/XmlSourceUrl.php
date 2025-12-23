<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\Xml;

use SimpleXMLElement;
use ZOOlanders\YOOessentials\Auth\AuthManager;
use function YOOtheme\app;

class XmlSourceUrl extends XmlSourceFile
{
    public const NAME = 'xml-url';

    protected string $configFile = 'config-url.json';

    protected function loadXml(bool $fresh = false): SimpleXMLElement
    {
        /** @var XmlService $xmlService */
        $xmlService = app(XmlService::class);

        $auth = null;
        if ($this->config('requires_auth')) {
            $auth = app(AuthManager::class)->auth($auth);
        }

        $cacheTime = self::getCacheTime($this, []);

        // Forcefull refresh
        if ($fresh) {
            $cacheTime = 0;
        }

        return $xmlService->loadFromUrl($this->config('url'), $auth, $cacheTime);
    }
}
