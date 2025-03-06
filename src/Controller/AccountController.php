<?php

namespace App\Controller;

use App\Entity\User as AppUser;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function accountPage(
        OrderRepository $orderRepository,
        UserRepository $userRepository,
    ): Response {
        $userEmail = $userRepository->find($this->getUser()?->getUserIdentifier());
        $user = $userRepository->findOneBy(['email' => $userEmail]);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $orders = $orderRepository->findOrderedForUser($user);

        return $this->render('accountPage/myAccount.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/account/api-access', name: 'app_account_api_access')]
    public function allowAPIAccess(
        UserRepository $userRepository,
        EntityManagerInterface $em,
    ): void {
        $user = $userRepository->find($this->getUser()?->getUserIdentifier());

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $user->addRole(AppUser::API_ACCESS);
        $em->persist($user);
        $em->flush();

    }
}
