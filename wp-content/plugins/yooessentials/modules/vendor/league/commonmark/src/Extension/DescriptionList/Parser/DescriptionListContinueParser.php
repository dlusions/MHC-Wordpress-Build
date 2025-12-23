<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare (strict_types=1);
namespace ZOOlanders\YOOessentials\Vendor\League\CommonMark\Extension\DescriptionList\Parser;

use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Extension\DescriptionList\Node\Description;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Extension\DescriptionList\Node\DescriptionList;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Extension\DescriptionList\Node\DescriptionTerm;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Node\Block\AbstractBlock;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Parser\Block\AbstractBlockContinueParser;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Parser\Block\BlockContinue;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Parser\Block\BlockContinueParserInterface;
use ZOOlanders\YOOessentials\Vendor\League\CommonMark\Parser\Cursor;
final class DescriptionListContinueParser extends AbstractBlockContinueParser
{
    private DescriptionList $block;
    public function __construct()
    {
        $this->block = new DescriptionList();
    }
    public function getBlock() : DescriptionList
    {
        return $this->block;
    }
    public function tryContinue(Cursor $cursor, BlockContinueParserInterface $activeBlockParser) : ?BlockContinue
    {
        return BlockContinue::at($cursor);
    }
    public function isContainer() : bool
    {
        return \true;
    }
    public function canContain(AbstractBlock $childBlock) : bool
    {
        return $childBlock instanceof DescriptionTerm || $childBlock instanceof Description;
    }
}
