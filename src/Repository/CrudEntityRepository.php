<?php

namespace App\Repository;

use App\Entity\CrudEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CrudEntity>
 *
 * @method CrudEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method CrudEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method CrudEntity[]    findAll()
 * @method CrudEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CrudEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CrudEntity::class);
    }

    //    /**
    //     * @return CrudEntity[] Returns an array of CrudEntity objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CrudEntity
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
