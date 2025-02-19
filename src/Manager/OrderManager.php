<?php

namespace App\Manager;

use App\Entity\Basket;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

final class OrderManager
{

    public function createOrderFromBasket(
        Basket $basket,
        EntityManagerInterface $em
    ): Order
    {
        $order = new Order();
        $order->setOwner($basket->getOwner());
        $order->setOrderedAt(new \DateTimeImmutable());
        foreach ($basket->getBasketProducts() as $basketProduct) {
            $basketProduct->setOrder($order);
            $order->addBasketProduct($basketProduct);
            $basket->removeBasketProduct($basketProduct);
            $basketProduct->setBasket(null);
        }

        $em->persist($order);
        $em->remove($basket);
        $em->flush();

        return $order;
    }

}
