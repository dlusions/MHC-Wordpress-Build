<?php

declare (strict_types=1);
/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ZOOlanders\YOOessentials\Vendor\League\CommonMark\Extension\DefaultAttributes;

use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Environment\EnvironmentBuilderInterface;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Event\DocumentParsedEvent;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Extension\ConfigurableExtensionInterface;
use ZOOlanders\YOOessentials\Vendor\League\Config\ConfigurationBuilderInterface;
use ZOOlanders\YOOessentials\Vendor\Nette\Schema\Expect;
final class DefaultAttributesExtension implements ConfigurableExtensionInterface
{
    public function configureSchema(ConfigurationBuilderInterface $builder) : void
    {
        $builder->addSchema('default_attributes', Expect::arrayOf(Expect::arrayOf(
            Expect::type('string|string[]|bool|callable'),
            // attribute value(s)
            'string'
        ), 'string')->default([]));
    }
    public function register(EnvironmentBuilderInterface $environment) : void
    {
        $environment->addEventListener(DocumentParsedEvent::class, [new ApplyDefaultAttributesProcessor(), 'onDocumentParsed']);
    }
}
