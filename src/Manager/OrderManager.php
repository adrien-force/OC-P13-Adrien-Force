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

        $owner = $basket->getOwner();
        if (null === $owner) {
            throw new \LogicException('Basket owner must be set before creating an order.');
        }
        $owner->addOrder(new Order());

        $em->persist($basket);
        $em->flush();
    }
}
