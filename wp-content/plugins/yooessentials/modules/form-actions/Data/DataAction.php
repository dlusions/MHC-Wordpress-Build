<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Data;

use ZOOlanders\YOOessentials\Form\Action\StandardAction;
use ZOOlanders\YOOessentials\Form\Http\FormSubmissionResponse;

class DataAction extends StandardAction
{
    public const NAME = 'data-add';

    public function __invoke(FormSubmissionResponse $response, callable $next): FormSubmissionResponse
    {
        $config = (object) $this->getConfig();

        $data = array_column($config->data, 'props');
        $formData = $response->submission()->data();

        foreach ($data as $d) {
            if (!isset($d['name'])) {
                continue;
            }

            $formData[$d['name']] = $d['value'] ?? null;
        }

        $response->submission()->setData($formData);

        return $next(
            $response->withDataLog([
                self::NAME => [
                    'config' => $config,
                ],
            ])
        );
    }
}
