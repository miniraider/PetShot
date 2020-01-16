<?php

namespace App\Repository;

use App\Entity\Publication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Publication|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publication|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publication[]    findAll()
 * @method Publication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publication::class);
    }

    public function format($publication, $cm)
    {
        $likes = array_map(function($lk) { return ['id' => $lk->getId(), 'user' => $lk->getUser()->getPseudo()]; }, $publication->getLikes()->toArray());
        $messages = array_map(function($msg) { return ['id' => $msg->getId(), 'user' => $msg->getUser()->getPseudo(), 'content' => $msg->getContent()]; }, $publication->getMessages()->toArray());
        $kill = ['id' => $publication->getKill()->getId(), 'animal' => $publication->getKill()->getAnimal()->getName(), 'animalCategory' => $publication->getKill()->getAnimal()->getCategory()->getName(),'score' => $publication->getKill()->getScore(), 'lat' =>  $publication->getKill()->getLat(), 'lat' =>  $publication->getKill()->getLng()];
        return [
            'id' => $publication->getId(),
            'dateAdd' => $publication->getDateAdd()->format('c'),
            'likes' => $likes,
            'message' => $messages,
            'kill' => $kill
        ];
    }

    // /**
    //  * @return Publication[] Returns an array of Publication objects
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
    public function findOneBySomeField($value): ?Publication
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
