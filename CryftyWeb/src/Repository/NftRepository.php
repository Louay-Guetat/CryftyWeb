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

    public function findSearch(\App\Data\SearchData $data) : array
    {
        $query = $this->createQueryBuilder('p')
        ->select('c','p')
        ->join('p.category','c')
        ->join('p.subCategory','s')
        ->join('p.currency','cu');

        if(!empty($data->min)){
            $query = $query->andWhere('p.price >= :min')
                ->setParameter('min',$data->min );
        }

        if(!empty($data->max)){
            $query = $query->andWhere('p.price <= :max')
                ->setParameter('max',$data->max );
        }

        if(!empty($data->categories)){
            $query = $query->andWhere('c.id IN (:categories)')
                ->setParameter('categories',$data->categories)
                ->orderBy('c.name','asc');
        }


        if(!empty($data->subCategories)){
            $query = $query->andWhere('s.id IN (:subCategory)')
                ->setParameter('subCategory',$data->subCategories)
                ->orderBy('s.name','asc');
        }

        if(!empty($data->currency)){
            $query = $query->andWhere('cu.id IN (:currency)')
                ->setParameter('currency',$data->currency);
        }

        if(!empty($data->q)){
            $query = $query->andWhere('p.title LIKE :q')
                ->setParameter('q','%'.$data->q.'%')
                ->orderBy('p.title','asc');
        }

        if($data->tri == 1){
            $query = $query->orderBy('p.price','asc');
        }

        if($data->tri == 0){
            $query = $query->orderBy('p.price','desc');
        }

        return $query->getQuery()->getResult();
    }



}
