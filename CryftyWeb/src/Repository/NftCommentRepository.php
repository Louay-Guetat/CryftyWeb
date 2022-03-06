<?php

namespace App\Repository;

use App\Entity\NFT\NftComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NftComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method NftComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method NftComment[]    findAll()
 * @method NftComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NftCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NftComment::class);
    }

    // /**
    //  * @return NftComment[] Returns an array of NftComment objects
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

    public function findAllByNft($value)
    {
        return $this->createQueryBuilder('C')
            ->where('C.nft = :value')
            ->setParameter('value',$value)
            ->orderBy('C.postDate','asc')
            ->getQuery()
            ->getResult();
    }

}
