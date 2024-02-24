<?php

namespace App\Repository;

use App\Entity\Testign;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Testign>
 *
 * @method Testign|null find($id, $lockMode = null, $lockVersion = null)
 * @method Testign|null findOneBy(array $criteria, array $orderBy = null)
 * @method Testign[]    findAll()
 * @method Testign[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestignRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Testign::class);
    }

    //    /**
    //     * @return Testign[] Returns an array of Testign objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Testign
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
