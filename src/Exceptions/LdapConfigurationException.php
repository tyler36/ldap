<?php

namespace Tyler36\Ldap\Exceptions;

use Exception;

class LdapConfigurationException extends Exception
{
    /**
     * LdapConfigurationException constructor
     *
     * @param mixed $attribute
     */
    public function __construct($attribute)
    {
        parent::__construct();

        $this->message  = "The following configuration is missing: ldap.${attribute}";
    }
}
