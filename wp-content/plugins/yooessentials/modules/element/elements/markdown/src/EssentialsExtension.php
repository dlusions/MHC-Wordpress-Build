<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Element\Markdown;

use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Environment\EnvironmentBuilderInterface;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Extension\ConfigurableExtensionInterface;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Event\DocumentPreParsedEvent;
use ZOOlanders\YOOessentials\Vendor\League\Config\ConfigurationBuilderInterface;
use ZOOlanders\YOOessentials\Vendor\Nette\Schema\Expect;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Extension\Table\Table;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Extension\CommonMark\Node\Block\ListBlock;

final class EssentialsExtension implements ConfigurableExtensionInterface
{
    public function configureSchema(ConfigurationBuilderInterface $builder): void
    {
        $builder->addSchema(
            'ye',
            Expect::structure([
                'heading_remove' => Expect::bool()->default(false),
                'heading_starting_level' => Expect::int()->default(0),
            ])
        );
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment
            ->addRenderer(Table::class, new Renderer\TableRenderer())
            ->addRenderer(ListBlock::class, new Renderer\ListBlockRenderer())
            ->addEventListener(DocumentPreParsedEvent::class, new HeadingMinLevel(), -100)
            ->addEventListener(DocumentPreParsedEvent::class, new HeadingRemoval(), -100);
    }
}
