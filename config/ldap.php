<?php

return [
    'host' => env('LDAP_HOST', ''),

    'username'        => env('LDAP_USERNAME', 'email'),
    'username_prefix' => env('LDAP_USERNAME_PREFIX', ''),
    'username_suffix' => env('LDAP_USERNAME_SUFFIX', ''),

    'common_name' => env('LDAP_COMMON_NAME', ''),
    'domain_comp' => env('LDAP_DOMAIN_COMP', 'dc=example,dc=local'),
    'filter'      => env('LDAP_FILTER', 'samaccountname'),

    'rules' => [
        env('LDAP_USERNAME', 'email') => 'required',
        'password'                => 'required',
    ],
];
