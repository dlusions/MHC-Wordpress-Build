<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Listener;

use YOOtheme\Http\Request;
use ZOOlanders\YOOessentials\Form\Controller\FormAdminController;
use ZOOlanders\YOOessentials\Form\Source\FormQueryType;
use ZOOlanders\YOOessentials\Form\Source\FormType;
use ZOOlanders\YOOessentials\Form\Source\FormOptionType;

class AddFormSource
{
    public Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle($source): void
    {
        if ($this->request->getParam('p') !== FormAdminController::FIELDS_URL) {
            return;
        }

        $controls = $this->request->getParam('controls', []);

        $source->objectType(FormType::NAME, FormType::config($controls));
        $source->objectType(FormOptionType::NAME, FormOptionType::config());

        $source->queryType(FormQueryType::config());
    }
}
