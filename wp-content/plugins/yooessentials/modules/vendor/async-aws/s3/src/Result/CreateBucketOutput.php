<?php

namespace ZOOlanders\YOOessentials\Vendor\AsyncAws\S3\Result;

use ZOOlanders\YOOessentials\Vendor\AsyncAws\Core\Response;
use ZOOlanders\YOOessentials\Vendor\AsyncAws\Core\Result;
class CreateBucketOutput extends Result
{
    /**
     * A forward slash followed by the name of the bucket.
     */
    private $location;
    public function getLocation() : ?string
    {
        $this->initialize();
        return $this->location;
    }
    protected function populateResult(Response $response) : void
    {
        $headers = $response->getHeaders();
        $this->location = $headers['location'][0] ?? null;
    }
}
