<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Redirect;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Form\Action\StandardAction;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;
use ZOOlanders\YOOessentials\UrlInterface;

class RedirectAction extends StandardAction
{
    public const NAME = 'redirect';

    public function __invoke(FormSubmissionResponse $response, callable $next): FormSubmissionResponse
    {
        return $next(
            $response->withData([
                'redirect' => [
                    'to' => app(UrlInterface::class)->cmsRoute($this->config('redirect', ''), false),
                    'timeout' => (int) $this->config('timeout', 0),
                    'blank' => $this->config('blank', false),
                ],
            ])
        );
    }
}
