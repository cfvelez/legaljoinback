<?php

namespace App\Repository;

use App\Entity\Storypoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Storypoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method Storypoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method Storypoint[]    findAll()
 * @method Storypoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StorypointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Storypoint::class);
    }

    // /**
    //  * @return Storypoint[] Returns an array of Storypoint objects
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
    public function findOneBySomeField($value): ?Storypoint
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
