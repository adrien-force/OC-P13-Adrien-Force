<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
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

        /**
         * @var array<Order> $basket
         */
        $basket =  $this->createQueryBuilder('b')
            ->where('b.owner = :userId')
            ->andWhere('b.status = :basket')
            ->setParameter('userId', $user->getId())
            ->setParameter('basket', 'basket')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;

        return $basket[0];
    }

    /**
     * @return list<Order>
     */
    public function findOrderedForUser(User $user): array
    {
        /** @var list<Order> $result */
        $result = $this->createQueryBuilder('b')
            ->where('b.owner = :userId')
            ->andWhere('b.status = :ordered')
            ->setParameter('userId', $user->getId(), Types::INTEGER)
            ->setParameter('ordered', 'ordered', Types::STRING)
            ->getQuery()
            ->getResult()
        ;

        return $result;
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
