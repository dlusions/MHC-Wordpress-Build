<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Element\Markdown;

use function YOOtheme\app;
use ZOOlanders\YOOessentials\Vendor\Symfony\Contracts\Cache\ItemInterface;
use ZOOlanders\YOOessentials\Vendor\Symfony\Contracts\Cache\CacheInterface;
use ZOOlanders\YOOessentials\Vendor\Symfony\Component\Cache\Adapter\FilesystemAdapter;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\MarkdownConverter;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Environment\Environment;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Extension\Table\TableExtension;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;

return [
    'transforms' => [
        'render' => function ($node) {
            /** @var CacheInterface|FilesystemAdapter $cache */
            $cache = app(CacheInterface::class);

            $mdconfig = [
                'ye' => [
                    'heading_remove' => $node->props['heading_remove'] ?? false,
                    'heading_starting_level' => (int) ($node->props['heading_starting_level'] ?? 0),
                ]
            ];

            $ctime = filectime(__FILE__);
            $md = $node->props['content'];

            $cacheKey = 'element-markdown' . hash('crc32b', json_encode([$md, $mdconfig, $ctime]));

            $node->content = $cache->get($cacheKey, function (ItemInterface $item) use ($md, $mdconfig) {
                try {
                    $environment = new Environment($mdconfig);

                    $environment
                        ->addExtension(new TableExtension())
                        ->addExtension(new CommonMarkCoreExtension())
                        ->addExtension(new EssentialsExtension());

                    $converter = new MarkdownConverter($environment);

                    return $converter->convert($md)->getContent();
                } catch (\Throwable $e) {
                    if (app()->config->get('app.isCustomizer')) {
                        return 'Error Processing Markdown: ' . $e->getMessage();
                    }
                }
            });

            // Don't render element if content fields is empty
            return (bool) $node->content;
        },
    ],
];
