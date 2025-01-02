<?php

namespace App\Repository;

use App\Entity\VideoRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method VideoRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoRole[]    findAll()
 * @method VideoRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoRole::class);
    }

    // /**
    //  * @return VideoRole[] Returns an array of VideoRole objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VideoRole
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
