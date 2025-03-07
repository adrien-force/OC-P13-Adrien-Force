<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UserManager
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function activateAPIAccess(User $user): void
    {
        $user->addRole(User::API_ACCESS);
        $this->em->persist($user);
        $this->em->flush();
    }
}
