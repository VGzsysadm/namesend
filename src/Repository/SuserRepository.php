<?php

namespace App\Repository;

use App\Entity\Suser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Suser|null find($id, $lockMode = null, $lockVersion = null)
 * @method Suser|null findOneBy(array $criteria, array $orderBy = null)
 * @method Suser[]    findAll()
 * @method Suser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Suser::class);
    }

    // /**
    //  * @return Suser[] Returns an array of Suser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Suser
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
