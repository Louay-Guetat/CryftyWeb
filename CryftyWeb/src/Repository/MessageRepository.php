<?php

namespace App\Repository;

use App\Entity\Chat\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    // /**
    //  * @return Message[] Returns an array of Message objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function AfficheMessages($idConversation)
    {
        $em=$this->getEntityManager();
        $query=$em->createQuery('SELECT m FROM App\Entity\Chat\Message m join m.conversation c where c.id=:idConversation')
        ->setParameter('idConversation',$idConversation);
        return $query->getResult();
    }
    public function AffichelastMsg($idConversation)
    {
        $em=$this->getEntityManager();
        $query=$em->createQuery('SELECT m.contenu FROM App\Entity\Chat\Message m
         join m.conversation c where c.id=:idConversation ORDER BY m.createdAt DESC  ')

            ->setParameter('idConversation',$idConversation)
            ->setMaxResults(1);
        return $query->getResult();
    }
    public function AffichelastIdmsg($idConversation,$idUser)
    {
        $em=$this->getEntityManager();
        $query=$em->createQuery('SELECT max(m.id) FROM App\Entity\Chat\Message m
         join m.conversation c where c.id=:idConversation and m.Sender=:idUser ')

            ->setParameter('idConversation',$idConversation)
            ->setParameter('idUser',$idUser)
            ->setMaxResults(1);
        return $query->getResult();
    }
}
