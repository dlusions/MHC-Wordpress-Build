<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Message;

use ZOOlanders\YOOessentials\Form\Action\StandardAction;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;

class MessageAction extends StandardAction
{
    public const NAME = 'message';

    public function __invoke(FormSubmissionResponse $response, callable $next): FormSubmissionResponse
    {
        return $next(
            $response->withData([
                'message_action' => [
                    'content' => $this->config('content', ''),
                    'stack' => $this->config('modal_stack', false),
                    'center' => $this->config('modal_center', false),
                    'onshown' => $this->config('modal_onshown', null),
                    'onhidden' => $this->config('modal_onhidden', null),
                ],
            ])
        );
    }
}
