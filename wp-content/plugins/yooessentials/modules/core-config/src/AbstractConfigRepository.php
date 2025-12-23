<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Config;

use YOOtheme\Event;
use YOOtheme\Http\Request;

abstract class AbstractConfigRepository
{
    public function load(ConfigInterface $config): void
    {
        $values = Event::emit('yooessentials.config.load|filter', $this->retrieve());

        $config->replace($values);
    }

    public function save(ConfigInterface $config): void
    {
        $values = Event::emit('yooessentials.config.save|filter', $config->toArray());

        $this->persist($values);
    }

    public function export(ConfigInterface $config): array
    {
        $values = Event::emit('yooessentials.config.export|filter', $config->toArray());

        return $values;
    }

    public function fromRequest(Request $request): ?array
    {
        // if in preview request
        if ($custom = $request('customizer')) {
            $params = json_decode(base64_decode($custom), true);

            return $params['yooessentials'] ?? null;
        }

        return null;
    }

    abstract protected function retrieve(): array;

    abstract protected function persist(array $values);
}
