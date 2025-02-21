<?php

namespace App\Controller;

use App\Entity\DeprecatedOrder;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{

    #[Route('/account', name: 'app_account')]
    public function accountPage
    (
        OrderRepository $orderRepository,
        UserRepository $userRepository
    ): Response
    {
        $userId = $userRepository->find($this->getUser()->getId());
        $user = $userRepository->findOneBy(['id' => $userId]);

        $orders = $orderRepository->findOrderedForUser($user);

        return $this->render('accountPage/myAccount.html.twig', [
            'orders' => $orders,
        ]);
    }
}
