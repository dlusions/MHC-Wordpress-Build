<?php

declare (strict_types=1);
namespace ZOOlanders\YOOessentials\Vendor\League\Flysystem\AsyncAwsS3;

use ZOOlanders\YOOessentials\Vendor\AsyncAws\S3\ValueObject\Grant;
interface VisibilityConverter
{
    public function visibilityToAcl(string $visibility) : string;
    /**
     * @param Grant[] $grants
     */
    public function aclToVisibility(array $grants) : string;
    public function defaultForDirectories() : string;
}
