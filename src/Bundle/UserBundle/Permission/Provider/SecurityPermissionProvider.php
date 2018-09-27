<?php

namespace Bundle\UserBundle\Permission\Provider;

class SecurityPermissionProvider implements ProviderInterface
{
    public function getPermissions()
    {
        return [
            'ROLE_ADMIN' => ['ROLE_USER']
        ];
    }
}