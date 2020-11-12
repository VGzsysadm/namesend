<?php

namespace App\Repository;

use App\Entity\MessagePassword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MessagePassword|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessagePassword|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessagePassword[]    findAll()
 * @method MessagePassword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessagePasswordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessagePassword::class);
    }

    // /**
    //  * @return MessagePassword[] Returns an array of MessagePassword objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MessagePassword
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
