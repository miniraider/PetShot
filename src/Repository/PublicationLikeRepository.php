<?php

namespace App\Repository;

use App\Entity\PublicationLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PublicationLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method PublicationLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method PublicationLike[]    findAll()
 * @method PublicationLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublicationLike::class);
    }

    // /**
    //  * @return PublicationLike[] Returns an array of PublicationLike objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PublicationLike
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
