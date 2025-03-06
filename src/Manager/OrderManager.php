<?php

namespace App\Manager;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

final class OrderManager
{
    public function createOrderFromBasket(
        Order $basket,
        EntityManagerInterface $em,
    ): void {
        $basket->setStatus(Order::ORDERED);
        $basket->setOrderedAt(new \DateTimeImmutable());
        $basket->getOwner()->addOrder(new Order());

        $em->persist($basket);
        $em->flush();
    }
}
