<?php

namespace ZOOlanders\YOOessentials\Vendor\AsyncAws\S3\Input;

use ZOOlanders\YOOessentials\Vendor\AsyncAws\Core\Input;
use ZOOlanders\YOOessentials\Vendor\AsyncAws\Core\Request;
use ZOOlanders\YOOessentials\Vendor\AsyncAws\Core\Stream\StreamFactory;
final class ListBucketsRequest extends Input
{
    /**
     * @param array{
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        parent::__construct($input);
    }
    public static function create($input) : self
    {
        return $input instanceof self ? $input : new self($input);
    }
    /**
     * @internal
     */
    public function request() : Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];
        // Prepare query
        $query = [];
        // Prepare URI
        $uriString = '/';
        // Prepare Body
        $body = '';
        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }
}
