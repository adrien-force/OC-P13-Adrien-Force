<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

final readonly class UserResolver
{
    public function __construct(
        private Security $security,
        private UserRepository $userRepository,
    ) {
    }

    public function getAuthenticatedUser(): User
    {
        $user = $this->security->getUser();

        if (null === $user) {
            throw new AuthenticationException('User not authenticated');
        }

        $userEmail = $user->getUserIdentifier();
        $user = $this->userRepository->findOneBy(['email' => $userEmail]);

        if (null === $user) {
            throw new NotFoundHttpException('User not found');
        }

        return $user;
    }
}
