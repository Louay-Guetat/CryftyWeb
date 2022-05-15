<?php

namespace App\Repository;

use App\Entity\Crypto\Wallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @method Wallet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wallet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wallet[]    findAll()
 * @method Wallet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WalletRepository extends ServiceEntityRepository
{


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wallet::class);

    }

    public function findSearch(Wallet $search) :array
    {
        $query = $this
            ->createQueryBuilder('w')
            ->select('n','c', 'w')
            ->join('w.nodeId', 'n')
            ->join('w.client', 'c')
            ->where('c.id = :client')
            ->setParameter('client',$search->getClient());


        if (!empty($search->getWalletAddress())) {
            $query = $query
                ->andWhere('w.walletAddress LIKE :q')
                ->setParameter('q', "%{$search->getWalletAddress()}%");
        }
        if (!empty($search->getWalletLabel())) {
            $query = $query
                ->andWhere('w.walletLabel LIKE :l')
                ->setParameter('l', "%{$search->getWalletLabel()}%");
        }


        if (!empty($search->getIsMain())) {
            $query = $query
                ->andWhere('w.isMain = 1');
        }

        if (!empty($search->getIsActive())) {
            $query = $query
                ->andWhere('w.isActive = 1');
        }

        if (!empty($search->getNodeId())) {
            $query = $query
                ->andWhere('n.id = :nodeId')
                ->setParameter('nodeId', $search->getNodeId());
        }

        $query->addOrderBy('w.isActive','DESC');
        $query->addOrderBy('w.isMain','DESC');
        return $query->getQuery()->getArrayResult();
    }

    // /**
    //  * @return Wallet[] Returns an array of Wallet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Wallet
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    function WalletBlock($id)
    {
        return $this->createQueryBuilder('w')
            ->where('w.client=:id')
            ->setParameter('id',$id)
        ;
    }
    function WalletClient($id)
    {
        return $this->createQueryBuilder('w')
            ->where('w.client=:id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult()
            ;
    }

}
