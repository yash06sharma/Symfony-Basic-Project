<?php

namespace App\Repository;

use App\Entity\BasicDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BasicDetail>
 *
 * @method BasicDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method BasicDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method BasicDetail[]    findAll()
 * @method BasicDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BasicDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BasicDetail::class);
    }

    //    /**
    //     * @return BasicDetail[] Returns an array of BasicDetail objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?BasicDetail
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
