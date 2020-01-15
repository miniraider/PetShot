<?php

namespace App\Repository;

use App\Entity\Animal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Animal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animal[]    findAll()
 * @method Animal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animal::class);
    }

    public function format($animal)
    {
        return ['id' => $animal->getId(), 'name' => $animal->getName(), 'category' => $animal->getCategory()->getName(), 'score' => $animal->getScore()];
    }

    public function formatForLeaderboard($animal, $cm){
        $kills = $cm->getRepository('App:UserKill')->findByAnimal($animal);
        $ret = [];
        foreach ($kills as $kill) {
            $ret[] = $cm->getRepository('App:UserKill')->format($kill, $cm);
        }
        return ['id' => $animal->getId(), 'name' => $animal->getName(), 'category' => $animal->getCategory()->getName(), 'score' => $animal->getScore(), 'kills' => $ret];
    }

    public function getAnimalsFiltered($name, $category, $habitat, $rarity, $score) {
        $query = $this->createQueryBuilder('a');

        $query->join('a.category', 'c');

        
        if ($name) $query->andWhere('a.name like :name')->orWhere('a.scientificName like :name')->setParameter('name', '%'.$name.'%');
        if ($category) $query->andWhere('c.name like :category')->setParameter('category', '%'.$category.'%');
        if ($rarity) $query->andWhere('a.rarety = :rarety')->setParameter('rarety', $rarity);
        if ($score) $query->andWhere('a.score >= :score')->setParameter('score', $score);

        if ($habitat) {
            $habitatList = explode("|", $habitat);

            $marine = false;
            $freshwater = false;
            $terrestrial = false;

            if (in_array("marine", $habitatList)) {
                $marine = true;
            }

            if (in_array("freshwater", $habitatList)) {
                $freshwater = true;
            }

            if (in_array("terrestrial", $habitatList)) {
                $terrestrial = true;
            }

            $query->andWhere('a.marine = :marine')->setParameter('marine', $marine)
            ->andWhere('a.freshwater = :freshwater')->setParameter('freshwater', $freshwater)
            ->andWhere('a.terrestrial = :terrestrial')->setParameter('terrestrial', $terrestrial);
        }
        
        return $query->getQuery()->getResult();
    }

    public function getLeaderboard($category){
        $request = $this->createQueryBuilder('a')
            ->leftJoin('a.kill', 'k')
            ->leftJoin('a.category', 'c');
        if($category){
            $request = $request->andWhere('c.id = :category')
                ->setParameter('category', $category);
        }
        return $request->getQuery()->getResult();
    }

    // /**
    //  * @return Animal[] Returns an array of Animal objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Animal
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
