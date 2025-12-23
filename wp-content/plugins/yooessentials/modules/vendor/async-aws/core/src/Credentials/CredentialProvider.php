<?php

declare (strict_types=1);
namespace ZOOlanders\YOOessentials\Vendor\AsyncAws\Core\Credentials;

use ZOOlanders\YOOessentials\Vendor\AsyncAws\Core\Configuration;
/**
 * Interface for providing Credential.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
interface CredentialProvider
{
    /**
     * Return a Credential when possible. Return null otherwise.
     */
    public function getCredentials(Configuration $configuration) : ?Credentials;
}
