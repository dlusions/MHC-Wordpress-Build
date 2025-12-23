<?php

namespace ZOOlanders\YOOessentials\Vendor\AsyncAws\Core\AwsError;

use ZOOlanders\YOOessentials\Vendor\Symfony\Contracts\HttpClient\ResponseInterface;
trait AwsErrorFactoryFromResponseTrait
{
    public function createFromResponse(ResponseInterface $response) : AwsError
    {
        $content = $response->getContent(\false);
        $headers = $response->getHeaders(\false);
        return $this->createFromContent($content, $headers);
    }
}
