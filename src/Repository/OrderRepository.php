<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
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

    public function findBasketForUser(User $user)
    {
        return $this->createQueryBuilder('b')
            ->where('b.owner = :userId')
            ->andWhere('b.status = :basket')
            ->setParameter('userId', $user->getId())
            ->setParameter('basket', 'basket')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    public function findOrderedForUser(User $user)
    {
        return $this->createQueryBuilder('b')
            ->where('b.owner = :userId')
            ->andWhere('b.status = :ordered')
            ->setParameter('userId', $user->getId())
            ->setParameter('ordered', 'ordered')
            ->getQuery()
            ->getResult();
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
