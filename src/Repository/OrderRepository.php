<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
use App\Exception\UnexpectedTypeException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findBasketForUser(User $user): ?Order
    {
        $basket =  $this->createQueryBuilder('b')
            ->where('b.owner = :userId')
            ->andWhere('b.status = :basket')
            ->setParameter('userId', $user->getId())
            ->setParameter('basket', 'basket')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        if (null !== $basket && !$basket instanceof Order) {
            throw new UnexpectedTypeException(Order::class, $basket);
        }

        return $basket;
    }

    public function findOrderedForUser(User $user): ?Order
    {
        $order =  $this->createQueryBuilder('b')
            ->where('b.owner = :userId')
            ->andWhere('b.status = :ordered')
            ->setParameter('userId', $user->getId())
            ->setParameter('ordered', 'ordered')
            ->getQuery()
            ->getResult();

        if (null !== $order && !$order instanceof Order) {
            throw new UnexpectedTypeException(Order::class, $order);
        }

        return $order;
    }

    //    /**
    //     * @return Order[] Returns an array of Order objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Order
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
