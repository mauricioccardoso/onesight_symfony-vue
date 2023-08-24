<?php

namespace App\Services;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityService
{
    public function __construct(
        private readonly Security $security
    ) { }

    public function getAuthUser(): UserInterface
    {
        return $this->security->getToken()?->getUser();
    }
}