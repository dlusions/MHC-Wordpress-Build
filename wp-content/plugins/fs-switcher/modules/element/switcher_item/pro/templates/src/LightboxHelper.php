<?php /**
 * @package     [FS] Switcher Pro for YOOtheme Pro
 * @subpackage  fs-switcher
 *
 * @author      Flart Studio https://flart.studio
 * @copyright   Copyright (C) 2026 Flart Studio. All rights reserved.
 * @license     GNU General Public License version 2 or later; see https://flart.studio/license
 * @link        https://flart.studio/yootheme-pro/switcher
 * @build       (FLART_BUILD_NUMBER)
 */

/** @noinspection NestedTernaryOperatorInspection, PhpUndefinedClassInspection, PhpUndefinedMethodInspection */

namespace FlartStudio\YOOtheme\Switcher;

defined('_JEXEC') or defined('ABSPATH') or die();

use YOOtheme\Http\Uri;
use YOOtheme\Image;
use YOOtheme\ImageProvider;
use YOOtheme\Url;
use Throwable;

use function YOOtheme\app;

class LightboxHelper
{
    /**
     * Generate Lightbox Attributes based on the YOOtheme version (V4/V5)
     *
     * @param object $view The YOOtheme View instance ($this)
     * @param array $element The element settings configuration
     * @param array $props The content properties
     * @param string $url The target URL for the lightbox
     *
     * @return array Returns an array of HTML attributes
     * @since 1.5.0
     */
    public static function getAttributes(object $view, array $element, array $props, string $url): array
    {
        // Default attributes
        $attrs = [
            'href' => $url,
            'class' => ['fs-switcher__item-link--lightbox'],
            'data-caption' => $view->expr([
                '<h4 class="uk-margin-remove">{title}</h4> {@title}' => $element['title_display'] !== 'item',
                '{content} {@content}' => $element['content_display'] !== 'item',
            ], $props) ?: false,
        ];

        // Version Check (Fail silently if neither V4 nor V5 is detected)
        if (empty($element['_yootheme_v4']) && empty($element['_yootheme_v5'])) {
            return $attrs;
        }

        try {
            // YOOtheme Pro v4 Processing
            if (($element['_yootheme_v4'] ?? false) === true) {
                $imageProvider = app(ImageProvider::class);
                if ($type = $view->isImage($url)) {
                    // Handle Thumbnails
                    if ($type !== 'svg' && ($element['lightbox_image_width'] || $element['lightbox_image_height'])) {
                        $thumbnail = [
                            $element['lightbox_image_width'],
                            $element['lightbox_image_height'],
                            $element['lightbox_image_orientation']
                        ];
                        if (!empty($props['lightbox_image_focal_point'])) {
                            [$y, $x] = explode('-', $props['lightbox_image_focal_point']);
                            $thumbnail[3] = $x;
                            $thumbnail[4] = $y;
                        }
                        $url = (string)(new Uri($url))->withFragment('thumbnail=' . implode(',', $thumbnail));
                    }
                    $attrs['href'] = $imageProvider->getUrl($url);
                    $attrs['data-alt'] = $props['image_alt'] ?? '';
                    $attrs['data-type'] = 'image';
                } else {
                    $attrs['data-type'] = $view->isVideo($url) ? 'video' : (!$view->iframeVideo($url) ? 'iframe' : true);
                }
            } // YOOtheme Pro v5 Processing
            elseif (($element['_yootheme_v5'] ?? false) === true) {
                if ($image = Image::create($url)) {
                    // Handle Thumbnails
                    if (($element['lightbox_image_width'] || $element['lightbox_image_height']) && $image->isResizable()) {
                        $thumbnail = [
                            $element['lightbox_image_width'],
                            $element['lightbox_image_height'],
                            (bool)$element['lightbox_image_orientation']
                        ];
                        if (!empty($props['lightbox_image_focal_point'])) {
                            [$y, $x] = explode('-', $props['lightbox_image_focal_point']);
                            $thumbnail[3] = $x;
                            $thumbnail[4] = $y;
                        }
                        $url = $image->thumbnail(...$thumbnail);
                    }
                    $attrs['href'] = Url::to($url);
                    $attrs['data-alt'] = $props['image_alt'] ?? '';
                    $attrs['data-type'] = 'image';
                } else {
                    $attrs['data-type'] = $view->isVideo($url) ? 'video' : (!$view->iframeVideo($url) ? 'iframe' : true);
                }
            }
            // Shared Styling (Text Color)
            if ($textColor = $props['lightbox_text_color'] ?: $element['lightbox_text_color']) {
                $attrs['data-attrs'] = "class: uk-inverse-$textColor";
            }
        } catch (Throwable) {
            // Silent fail: Return standard attributes if image processing fails
        }

        return $attrs;
    }
}