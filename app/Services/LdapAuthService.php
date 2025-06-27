// app/Services/LdapAuthService.php
<?php

namespace App\Services;

use Adldap\Adldap;
use Adldap\Connections\Provider;
use Adldap\Connections\Ldap;

class LdapAuthService
{
    private $provider;
    
    public function __construct()
    {
        if (config('ldap.enabled')) {
            $this->initializeLdap();
        }
    }
    
    private function initializeLdap(): void
    {
        $config = [
            'hosts' => [config('ldap.host')],
            'base_dn' => config('ldap.base_dn'),
            'username' => config('ldap.bind_username'),
            'password' => config('ldap.bind_password'),
            'port' => config('ldap.port', 389),
            'use_ssl' => config('ldap.use_ssl', false),
            'use_tls' => config('ldap.use_tls', false),
            'timeout' => 5,
        ];
        
        $ldap = new Ldap();
        $this->provider = new Provider($config, $ldap);
    }
    
    public function authenticate(string $username, string $password): array
    {
        if (!$this->provider) {
            throw new \Exception('LDAP not configured');
        }
        
        try {
            // Connect to LDAP
            $this->provider->connect();
            
            // Search for user
            $usernameAttr = config('ldap.username_attribute', 'samaccountname');
            $user = $this->provider->search()
                ->where($usernameAttr, '=', $username)
                ->firstOrFail();
            
            // Attempt to bind with user credentials
            $userDn = $user->getDistinguishedName();
            
            if (!$this->provider->auth()->attempt($userDn, $password)) {
                return ['success' => false, 'message' => 'Invalid credentials'];
            }
            
            // Get user information
            $email = $this->getAttribute($user, config('ldap.email_attribute', 'mail'));
            $name = $this->getAttribute($user, config('ldap.name_attribute', 'displayname'));
            
            // Check group membership
            $userGroups = $user->getGroups();
            $isAdmin = $this->isUserInGroup($userGroups, config('ldap.admin_group'));
            $isUser = $this->isUserInGroup($userGroups, config('ldap.user_group'));
            
            if (!$isAdmin && !$isUser) {
                return ['success' => false, 'message' => 'User not in allowed groups'];
            }
            
            return [
                'success' => true,
                'email' => $email ?: $username,
                'name' => $name ?: $username,
                'is_admin' => $isAdmin,
                'is_user' => $isUser
            ];
            
        } catch (\Exception $e) {
            logger('LDAP authentication error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    private function getAttribute($user, string $attribute): ?string
    {
        $value = $user->getAttribute($attribute);
        
        if (is_array($value)) {
            return $value[0] ?? null;
        }
        
        return $value;
    }
    
    private function isUserInGroup($userGroups, string $targetGroup): bool
    {
        if (empty($targetGroup)) {
            return false;
        }
        
        foreach ($userGroups as $group) {
            if (stripos($group->getDistinguishedName(), $targetGroup) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    public function isEnabled(): bool
    {
        return config('ldap.enabled', false);
    }
}