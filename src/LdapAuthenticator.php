<?php

namespace Tyler36\Ldap;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * LdapAuthenticator class
 */
class LdapAuthenticator
{
    protected $connector;

    protected $credentials = [];

    protected $request;

    /**
     * LdapAuthenticator constructor
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request   = $request;
    }

    /**
     * Get an instance of the LDAP connector
     *
     * @return \Tyler36\Ldap\LdapConnector
     */
    public function getConnector()
    {
        return tap(new LdapConnector())->connect();
    }

    /**
     * Authenticate user via request parameters
     *
     * @return mixed
     */
    public function authenticate()
    {
        $validator     = $this->getValidator();
        if ($validator->fails()) {
            return false;
        }
        $credentials = $validator->validated();

        $connector   = $this->getConnector();
        $bind        = $connector->bind(
            $this->fullUsername($credentials['username']),
            $credentials['password']
        );

        if ($bind) {
            return $connector->search($credentials['username']);
        }

        return false;
    }

    /**
     * Rules used by validator
     *
     * @return array
     */
    public function getRules()
    {
        return config('ldap.rules');
    }

    /**
     * Get username type
     *
     * @return string
     */
    public function getUsernameType()
    {
        return config('ldap.username');
    }

    /**
     * Validate credentials
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidator()
    {
        return Validator::make($this->request->all(), $this->getRules());
    }

    /**
     * Add prefix / suffix to username
     *
     * @param string $username
     *
     * @return string
     */
    public function fullUsername($username)
    {
        return config('ldap.username_prefix') . $username . config('ldap.username_suffix');
    }
}
