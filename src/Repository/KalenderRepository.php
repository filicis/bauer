<?php

namespace App\Repository;

use App\Entity\Kalender;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Kalender|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kalender|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kalender[]    findAll()
 * @method Kalender[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KalenderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kalender::class);
    }

    // /**
    //  * @return Kalender[] Returns an array of Kalender objects
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
    public function findOneBySomeField($value): ?Kalender
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
