<?php

namespace App\Repository;

use App\Entity\Payment\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    // /**
    //  * @return Cart[] Returns an array of Cart objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cart
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /*public function findOneBySomeField($value): ?Cart
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }*/
    public function compareId($id): ?Cart
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.clientId = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findByClient($id){
        return $this->createQueryBuilder('C')
            ->where('C.clientId = :id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult();
    }

    public function AfficherCart($id)
    {
        $em=$this->getEntityManager();
        $query=$em->createQuery('SELECT c from APP\Entity\Payment\Cart c where c.id =: id')
            ->setParameter('id',$id);
        return $query->getOneOrNullResult();
    }

    public function SuppNft($id)
    {
        $query=$this->createQueryBuilder('c')
            ->join('c.nftProd','p')
            ->where('p.id=:id')
            ->setParameter('id',$id)
            ->getQuery();
        return $query->getResult();
    }


}
