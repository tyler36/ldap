<?php

namespace Tyler36\Ldap\Exceptions;

use Exception;

class LdapBindException extends Exception
{
    /**
     * LdapConfigurationException constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->message  = 'Unable to bind to server. General error or Invalid credentials';
    }
}
