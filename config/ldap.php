<?php

return [
    'enabled' => env('LDAP_ENABLED', false),
    'host' => env('LDAP_HOST', '127.0.0.1'),
    'port' => env('LDAP_PORT', 389),
    'base_dn' => env('LDAP_BASE_DN', 'dc=local,dc=com'),
    'bind_username' => env('LDAP_BIND_USERNAME', ''),
    'bind_password' => env('LDAP_BIND_PASSWORD', ''),
    'use_ssl' => env('LDAP_USE_SSL', false),
    'use_tls' => env('LDAP_USE_TLS', false),
    'admin_group' => env('LDAP_ADMIN_GROUP', ''),
    'user_group' => env('LDAP_USER_GROUP', ''),
    'username_attribute' => env('LDAP_USERNAME_ATTRIBUTE', 'samaccountname'),
    'email_attribute' => env('LDAP_EMAIL_ATTRIBUTE', 'mail'),
    'name_attribute' => env('LDAP_NAME_ATTRIBUTE', 'displayname'),
];