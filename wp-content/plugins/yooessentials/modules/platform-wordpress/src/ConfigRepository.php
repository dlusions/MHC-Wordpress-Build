<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Wordpress;

use ZOOlanders\YOOessentials\Config\AbstractConfigRepository;
use ZOOlanders\YOOessentials\Config\ConfigRepositoryInterface;

class ConfigRepository extends AbstractConfigRepository implements ConfigRepositoryInterface
{
    protected const NAME = 'yooessentials';

    public function retrieve(): array
    {
        $data = json_decode(get_option(self::NAME), true);

        return is_array($data) ? $data : [];
    }

    public function authorize(): bool
    {
        return current_user_can('edit_theme_options');
    }

    public function persist(array $values): void
    {
        $data = json_encode($values);
        if (!$data) {
            return;
        }

        update_option(self::NAME, $data);
    }
}
