<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Element\Markdown;

use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Event\DocumentPreParsedEvent;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Input\MarkdownInput;
use ZOOlanders\YOOessentials\Vendor\League\Config\ConfigurationAwareInterface;
use ZOOlanders\YOOessentials\Vendor\League\Config\ConfigurationInterface;

/**
 * Searches the Markdown for the Heading with level 1 and removes it
 */
final class HeadingRemoval implements ConfigurationAwareInterface
{
    private ConfigurationInterface $config;

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->config = $configuration;
    }

    public function __invoke(DocumentPreParsedEvent $e): void
    {
        $removeHeading = $this->config->get('ye/heading_remove');

        if ($removeHeading) {
            $md = $e->getMarkdown()->getContent();
            $md = preg_replace('/^# (.*)$/m', '', $md);

            $e->replaceMarkdown(new MarkdownInput($md));
        }
    }
}
