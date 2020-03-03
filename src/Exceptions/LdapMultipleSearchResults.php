<?php

namespace Tyler36\Ldap\Exceptions;

use Exception;

class LdapMultipleSearchResults extends Exception
{
    /**
     * LdapMultipleSearchResults constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->message  = 'More than 1 entry was found';
    }
}
