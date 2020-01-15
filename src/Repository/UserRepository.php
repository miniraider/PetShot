<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function format($user, $cm)
    {
        $formatedKills = [];
        $kills = $cm->getRepository('App:UserKill')->findByUser($user);
        foreach ($kills as $kill) {
            $formatedKills[] = $cm->getRepository('App:UserKill')->format($kill, $cm);
        }
        $followers = array_map(function($usr) { return ['id' => $usr->getId(), 'pseudo' => $usr->getPseudo()]; }, $user->getFollowers()->toArray());
        return ['id' => $user->getId(), 'kills' => $formatedKills, 'name' => $user->getName(), 'lastName' => $user->getLastName(), 'description' => $user->getDescription(), 'pseudo' => $user->getPseudo(), 'followers' => $followers];
    }

    public function getMatchUsers($name)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.name like :str')
            ->orWhere('u.pseudo like :str')
            ->orWhere('u.lastName like :str')
            ->setParameter('str', '%'.$name.'%')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getFollowed($user, $cm)
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.followers', 'fl')
            ->andWhere('fl.id = :id')
            ->setParameter('id', $user->getId())
            ->getQuery()
            ->getResult()
        ;
    }

    public function getLeaderboard($zone){

        $request = $this->createQueryBuilder('u')
            ->leftJoin('u.kill', 'k');
        if($zone){
            $request = $request->andWhere('u.nationality = :zone')
                ->setParameter('zone', $zone);
        }
        return $request->getQuery()->getResult();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
