<?php

namespace App\Repository;

use App\Entity\NFT\Nft;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Nft|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nft|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nft[]    findAll()
 * @method Nft[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NftRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nft::class);
    }

    // /**
    //  * @return Nft[] Returns an array of Nft objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Nft
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function SuppNft($id)
    {
        $query=$this->createQueryBuilder('n')
            ->join('n.cartProd','c')
            ->where('c.id=:id')
            ->setParameter('id',$id)
            ->getQuery();
        return $query->getResult();
    }
}
