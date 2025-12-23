<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Source\Provider\CloudflareStream;

use YOOtheme\Url;
use YOOtheme\View;

class ViewHelper extends \YOOtheme\Theme\ViewHelper
{
    const CLOUDFLARE_IFRAME = 'iframe.videodelivery.net';

    /**
     * @param View $view
     * @return void
     */
    public function register($view)
    {
        $view->addFunction('iframeVideo', [$this, 'iframeVideo']);

        $view['html']->addTransform('iframe', function ($element, $params) {
            $src = $element->attrs['src'] ?? '';

            // add cloudflare stream iframe poster support to video element
            if (is_string($src) && strpos($src, self::CLOUDFLARE_IFRAME) !== false) {
                $poster = $params['video_poster'] ?? null;

                $element->attrs['src'] = Url::to($src, [
                    'poster' => $poster ? Url::to("~/$poster", [], true) : null,
                    'loop' => $params['video_loop'] ?? 0,
                    'muted' => $params['video_muted'] ?? 0,
                    'autoplay' => $params['video_autoplay'] ?? 0,
                    'controls' => $params['video_controls'] ?? 1,
                    'preload' => $params['video_lazyload'] ? 'none' : true,
                ]);
            }
        });
    }

    public function iframeVideo($link, $params = [], $defaults = true)
    {
        // add cloudflare stream iframe support
        if (is_string($link) && strpos($link, self::CLOUDFLARE_IFRAME) !== false) {
            return Url::to($link);
        }

        return parent::iframeVideo($link, $params, $defaults);
    }
}
