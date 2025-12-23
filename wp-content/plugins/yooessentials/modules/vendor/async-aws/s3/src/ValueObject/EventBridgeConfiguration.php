<?php

namespace ZOOlanders\YOOessentials\Vendor\AsyncAws\S3\ValueObject;

/**
 * A container for specifying the configuration for Amazon EventBridge.
 */
final class EventBridgeConfiguration
{
    public static function create($input) : self
    {
        return $input instanceof self ? $input : new self();
    }
    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document) : void
    {
    }
}
