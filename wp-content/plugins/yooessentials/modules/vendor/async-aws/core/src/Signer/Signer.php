<?php

namespace ZOOlanders\YOOessentials\Vendor\AsyncAws\Core\Signer;

use ZOOlanders\YOOessentials\Vendor\AsyncAws\Core\Credentials\Credentials;
use ZOOlanders\YOOessentials\Vendor\AsyncAws\Core\Request;
use ZOOlanders\YOOessentials\Vendor\AsyncAws\Core\RequestContext;
/**
 * Interface for signing a request.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
interface Signer
{
    public function sign(Request $request, Credentials $credentials, RequestContext $context) : void;
    public function presign(Request $request, Credentials $credentials, RequestContext $context) : void;
}
