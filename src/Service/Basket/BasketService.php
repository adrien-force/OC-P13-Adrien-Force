<?php

namespace App\Service\Basket;

use App\Entity\Order;
use App\Entity\User;
use App\Repository\OrderRepository;
use App\Service\Order\OrderService;
use Doctrine\ORM\EntityManagerInterface;

class BasketService
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly EntityManagerInterface $em,
        private readonly OrderService $orderService,
    ) {}
    public function getBasketOrCreateForUser(User $user): Order
    {
        $order = $this->orderRepository->findBasketForUser($user);

        if (!$order instanceof Order) {
            $order = $this->orderService->createOrderForUser($user, Order::BASKET);
        }

        return $order;
    }

    public function clearBasket(Order $order): void
    {
        if (Order::ORDERED === $order->getStatus()) {
            throw new \LogicException('Order is no longer a basket and should not be cleared.');
        }

        $orderItems = $order->getOrderItems();
        foreach ($orderItems as $orderProduct) {
            $this->em->remove($orderProduct);
        }
        $this->em->persist($order);
        $this->em->flush();
    }

}
