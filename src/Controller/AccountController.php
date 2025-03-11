<?php

namespace App\Controller;

use App\Manager\UserManager;
use App\Repository\OrderRepository;
use App\Service\UserResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    public function __construct(private readonly UserResolver $userResolver)
    {
    }

    #[Route('/account', name: 'app_account')]
    public function accountPage(
        OrderRepository $orderRepository,
    ): Response {
        $user = $this->userResolver->getAuthenticatedUser();

        $orders = $orderRepository->findOrderedForUser($user);

        return $this->render('accountPage/myAccount.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/account/api-access', name: 'app_account_api_access')]
    public function allowAPIAccess(
        UserManager $userManager,
    ): void {
        $user = $this->userResolver->getAuthenticatedUser();
        $userManager->activateAPIAccess($user);
    }
}
