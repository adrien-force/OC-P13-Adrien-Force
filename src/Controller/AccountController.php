<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{

    #[Route('/account', name: 'app_account')]
    public function accountPage
    (
        OrderRepository $orderRepository
    ): Response
    {
        $orders = $orderRepository->findBy([], ['id' => 'ASC']);

        return $this->render('accountPage/myAccount.html.twig', [
            'orders' => $orders,
        ]);
    }
}
