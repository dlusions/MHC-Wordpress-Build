<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 * (c) 2015 Martin Hasoň <martin.hason@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare (strict_types=1);
namespace ZOOlanders\YOOessentials\Vendor\League\CommonMark\Extension\Attributes;

use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Environment\EnvironmentBuilderInterface;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Event\DocumentParsedEvent;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Extension\Attributes\Event\AttributesListener;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Extension\Attributes\Parser\AttributesBlockStartParser;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Extension\Attributes\Parser\AttributesInlineParser;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Extension\ConfigurableExtensionInterface;
use ZOOlanders\YOOessentials\Vendor\League\Config\ConfigurationBuilderInterface;
use ZOOlanders\YOOessentials\Vendor\Nette\Schema\Expect;
final class AttributesExtension implements ConfigurableExtensionInterface
{
    public function configureSchema(ConfigurationBuilderInterface $builder) : void
    {
        $builder->addSchema('attributes', Expect::structure(['allow' => Expect::arrayOf('string')->default([])]));
    }
    public function register(EnvironmentBuilderInterface $environment) : void
    {
        $allowList = $environment->getConfiguration()->get('attributes.allow');
        $allowUnsafeLinks = $environment->getConfiguration()->get('allow_unsafe_links');
        $environment->addBlockStartParser(new AttributesBlockStartParser());
        $environment->addInlineParser(new AttributesInlineParser());
        $environment->addEventListener(DocumentParsedEvent::class, [new AttributesListener($allowList, $allowUnsafeLinks), 'processDocument']);
    }
}
