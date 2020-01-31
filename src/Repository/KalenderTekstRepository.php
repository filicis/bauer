<?php

namespace App\Repository;

use App\Entity\KalenderTekst;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method KalenderTekst|null find($id, $lockMode = null, $lockVersion = null)
 * @method KalenderTekst|null findOneBy(array $criteria, array $orderBy = null)
 * @method KalenderTekst[]    findAll()
 * @method KalenderTekst[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KalenderTekstRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KalenderTekst::class);
    }

    // /**
    //  * @return KalenderTekst[] Returns an array of KalenderTekst objects
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
    public function findOneBySomeField($value): ?KalenderTekst
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
