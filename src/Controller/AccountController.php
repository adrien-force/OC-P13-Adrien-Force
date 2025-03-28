<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Service\User\ApiAccessService;
use App\Service\UserResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    public function __construct(
        private readonly UserResolver $userResolver,
        private readonly ApiAccessService $apiAccessService,
    ) {}

    #[Route('/account', name: 'app_account')]
    public function accountPage(
        OrderRepository $orderRepository,
    ): Response {
        $user = $this->userResolver->getAuthenticatedUser();

        $orders = $orderRepository->findOrderedForUser($user);

        return $this->render('accountPage/myAccount.html.twig', [
            'orders' => $orders,
            'user' => $user,
        ]);
    }

    #[Route('/account/allow-api-access', name: 'app_account_allow_api_access')]
    public function allowAPIAccess(
    ): Response {
        $user = $this->userResolver->getAuthenticatedUser();
        $this->apiAccessService->activateAPIAccess($user);

        return $this->redirectToRoute('app_security_notice', ['message' => "Votre accès API a été activé. \n Veuillez vous reconnecter."]);
    }

    #[Route('/account/disable-api-access', name: 'app_account_disable_api_access')]
    public function disableAPIAccess(
    ): Response {
        $user = $this->userResolver->getAuthenticatedUser();
        $this->apiAccessService->disableAPIAccess($user);

        return $this->redirectToRoute('app_security_notice', ["message' => 'Votre accès API a été désactivé. \n Veuillez vous reconnecter."]);

    }

}
