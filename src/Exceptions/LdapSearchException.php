<?php

namespace Tyler36\Ldap\Exceptions;

use Exception;

class LdapSearchException extends Exception
{
    /**
     * LdapSearchException constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->message  = 'There was a problem searching for the directory.';
    }
}
