<?php

namespace App\Repository;

use App\Entity\Payment\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    // /**
    //  * @return Transaction[] Returns an array of Transaction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Transaction
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function afficherTransaction($id)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.cartId = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
            ;
    }
    public function name($val)
    {
        $em=$this->getEntityManager();
        $query=$em->createQuery('SELECT c.firstName from App\Entity\Users\Client c join App\Entity\Payment\Cart p join App\Entity\Payment\Transaction t where c.id = p.clientId and p.id = t.cartId and c.firstName =:val')
        ->setParameter('val',$val);
        return $query->getResult();
    }
}
