<?php

namespace App\Repository;

use App\Entity\Chat\PrivateChat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PrivateChat|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrivateChat|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrivateChat[]    findAll()
 * @method PrivateChat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrivateChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrivateChat::class);
    }

    // /**
    //  * @return PrivateChat[] Returns an array of PrivateChat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PrivateChat
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
  public function Privatechat($id1,$id2)
    {
        $em=$this->getEntityManager();
        $query=$em->createQuery('SELECT m FROM App\Entity\Chat\PrivateChat m 
          where m.Sender=:id1 and m.Received=:id2 or m.Sender=:id2 and m.Received=:id1')
            ->setParameter('id1',$id1)
            ->setParameter('id2',$id2);
        return $query->getResult();
    }


    public function nomPrivatChat($id)
    {
        $em=$this->getEntityManager();
        $query=$em->createQuery('SELECT m FROM App\Entity\Chat\PrivateChat m 
          where m.id=:id')
            ->setParameter('id',$id);

        return $query->getResult();
    }


    public function UsersContacter($id)
    {
        $em=$this->getEntityManager();
        $query=$em->createQuery('SELECT m FROM App\Entity\Chat\PrivateChat m 
          where  m.Sender=:id or m.Received=:id')
            ->setParameter('id',$id);

        return $query->getResult();
    }

}
