<?php

namespace App\Repository;

use App\Entity\UserKill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UserKill|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserKill|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserKill[]    findAll()
 * @method UserKill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserKillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserKill::class);
    }

    public function format($kill, $cm)
    {
        return ['id' => $kill->getId(), 'animal' => $kill->getAnimal()->getName(), 'animalCategory' => $kill->getAnimal()->getCategroy()->getId(), 'score' => $kill->getScore()];
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
