<?php

namespace App\Service\Order;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}
    public function createOrderFromBasket(
        Order $basket,
    ): void {
        $basket->setStatus(Order::ORDERED);
        $basket->setOrderedAt(new \DateTimeImmutable());

        $owner = $basket->getOwner();
        if (null === $owner) {
            throw new \LogicException('Basket owner must be set before creating an order.');
        }
        $owner->addOrder(new Order());

        $this->em->persist($basket);
        $this->em->flush();
    }

    public function createOrderForUser(User $user, string $status = Order::BASKET): Order
    {
        $order = new Order();
        $order->setOwner($user);
        $order->setStatus($status);
        $user->addOrder($order);

        $this->em->persist($order);
        $this->em->flush();

        return $order;
    }

}
