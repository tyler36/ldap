<?php

namespace Tyler36\Ldap;

use Exception;
use Tyler36\Ldap\Exceptions\LdapBindException;
use Tyler36\Ldap\Exceptions\LdapConfigurationException;
use Tyler36\Ldap\Exceptions\LdapSearchException;

/**
 * LdapConnector class
 */
class LdapConnector
{
    // @var LDAP link identifier
    protected $connection;

    // @var string      Host to connect to
    protected $host;

    // @var string      Password
    protected $password;

    // @var string      Username
    protected $username;

    /**
     * LdapConnector constructor
     */
    public function __construct()
    {
        $this->host = config('ldap.host');

        if (!$this->host) {
            throw new LdapConfigurationException('host');
        }
    }

    /**
     * Creates an LDAP link identifier and checks whether the given host and port are plausible.
     *
     * @return false|resource LDAP link identifier
     */
    public function connect()
    {
        return $this->connection = ldap_connect($this->host);
    }

    /**
     * Binds to the LDAP directory with specified RDN and password.
     *
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function bind($username, $password)
    {
        try {
            return ldap_bind($this->connection, $username, $password);
        } catch (Exception $error) {
            throw new LdapBindException();
        }
    }

    /**
     * Search
     *
     * @param string $username
     *
     * @return false|object
     */
    public function search($username)
    {
        $search  = ldap_search($this->connection, $this->getBaseDn(), config('ldap.filter') . "=${username}");
        if (!$search) {
            throw new LdapSearchException();
        }

        $results = ldap_get_entries($this->connection, $search);
        if (1 === $results['count']) {
            return $results[0];
        }

        throw new LdapSearchException();
    }

    /**
     * Compute the 'base_dn'
     *
     * @return string
     */
    public function getBaseDn()
    {
        $string = config('ldap.domain_comp');
        if ($cn = config('ldap.common_name', '')) {
            $string = "${cn},${string}";
        }

        return $string;
    }
}
