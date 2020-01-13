<?php

namespace App\Repository;

use App\Entity\Kill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Kill|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kill|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kill[]    findAll()
 * @method Kill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kill::class);
    }

    // /**
    //  * @return Kill[] Returns an array of Kill objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Kill
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
