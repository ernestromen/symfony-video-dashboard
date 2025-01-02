<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }

     /**
      * @return Video[] Returns an array of Video objects
      */
    
    public function findByUserId(EntityManagerInterface $em, $currentLoggedInUserId)
    {
                    // SELECT * FROM `videos`
            //  join video_role ON videos.id = video_role.video_id
            //   JOIN user_role ON video_role.role_id = user_role.role_id
            //    WHERE user_role.user_id = '73435229-225e-4c21-abc6-8479da318e9e' 
            // return $this->createQueryBuilder('v')
            // ->innerJoin('v.videoRoles', 'vr') // join with the video_role table (assuming videoRoles is the relation)
            // ->innerJoin('vr.role', 'r') // join with the role (assuming role is the relation in video_role)
            // ->innerJoin('r.userRoles', 'ur') // join with user_role table (assuming userRoles is the relation in Role)
            // ->where('ur.user = :userId') // filter by the user_id in user_role
            // ->setParameter('userId', $currentLoggedInUserId) // bind the user ID parameter
            // ->orderBy('v.id', 'ASC') // ordering by video ID
            // ->getQuery()
            // ->getResult();
            
            $qb = $em->createQueryBuilder();

            $qb->select('v')
                ->from('App\Entity\Video', 'v')
                ->join('v.videoRoles', 'vr')
                ->join('vr.role', 'r')
                ->join('r.userRoles', 'ur')
                ->where('ur.user = :userId')
                ->setParameter('userId', $currentLoggedInUserId);
        
            return $qb->getQuery()->getResult();
    }
}
