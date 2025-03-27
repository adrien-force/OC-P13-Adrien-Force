<?php

namespace App\DataFixtures;

use App\Factory\OrderFactory;
use App\Factory\OrderItemFactory;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /** USERS  */

        //Admin user
        $adminUser = UserFactory::createOne(
            [
                'firstname' => 'Monsieur',
                'lastname' => 'Admin',
                'email' => 'admin@greengoodies.com',
            ],
        );

        //Basic user
        $basicUser = UserFactory::createOne(
            [
                'firstname' => 'Monsieur',
                'lastname' => 'User',
                'email' => 'user@greengoodies.com',
            ],
        );

        //Dummy data
        UserFactory::createMany(5);

        /** PRODUCTS */
        $products = ProductFactory::createMany(
            9,
            static function (int $i) {
                return [
                    'imgSrc' => sprintf('product%d.avif', $i),
                ];
            },
        );

        /** ORDER ITEMS */

        $adminBasketOrderItems = OrderItemFactory::createMany(
            5,
            static function (int $i) use ($products) {
                return [
                    'quantity' => random_int(1, 5),
                    'product' => $products[$i],
                ];
            },
        );

        $userBasketOrderItems = OrderItemFactory::createMany(
            5,
            static function (int $i) use ($products) {
                return [
                    'quantity' => random_int(1, 5),
                    'product' => $products[$i + 1],
                ];
            },
        );

        $adminOrderedOrderItems = OrderItemFactory::createMany(
            5,
            static function (int $i) use ($products) {
                return [
                    'quantity' => random_int(1, 5),
                    'product' => $products[$i],
                ];
            },
        );

        $userOrderedOrderItems = OrderItemFactory::createMany(
            5,
            static function (int $i) use ($products) {
                return [
                    'quantity' => random_int(1, 5),
                    'product' => $products[$i + 1],
                ];
            },
        );

        /** BASKET */

        OrderFactory::createOne(
            [
                'owner' => $adminUser,
                'orderItems' => $adminBasketOrderItems,
            ],
        );

        OrderFactory::createOne(
            [
                'owner' => $basicUser,
                'orderItems' => $userBasketOrderItems,
            ],
        );

        /** ORDERED */

        OrderFactory::createOne(
            [
                'owner' => $adminUser,
                'status' => 'ordered',
                'orderItems' => $adminOrderedOrderItems,
            ],
        );

        OrderFactory::createOne(
            [
                'owner' => $basicUser,
                'status' => 'ordered',
                'orderItems' => $userOrderedOrderItems,
            ],
        );

    }
}
