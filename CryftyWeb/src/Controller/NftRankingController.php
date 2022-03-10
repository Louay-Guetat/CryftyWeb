<?php

namespace App\Controller;

use App\Entity\Users\Client;
use App\Repository\ClientRepository;
use App\Repository\NftRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NftRankingController extends AbstractController
{

    /**
     * @Route ("nft/ranking",name="nftRanking")
     */
    function nftRanking(NftRepository $nftRepository,ClientRepository $clientRepo
                        ,Request $request,PaginatorInterface $paginator){
        $nft = $nftRepository->findAll();
        $nfts = $paginator->paginate($nft, $request->query->getInt('page',1),10);
        $client = $clientRepo->findAll();
        $clients= $paginator->paginate($client, $request->query->getInt('page',1),10);
        $numberNfts = [];
        $volume = [];
        $floorPrice=[];
        $i=0;
        foreach ($client as $individual){
            $nombre=0;
            $prices = 0.0;
            foreach($nft as $item){
                if($item->getOwner() === $individual){
                    $nombre++;
                    $prices = $prices + $item->getPrice();
                }
            }
            $numberNfts[$i]=$nombre;
            $volume[$i] = $prices;
            if($nombre!=0) {
                $floorPrice[$i] = $prices / $nombre;
            }else $floorPrice[$i] =0;
            $i++;
        }
        return $this->render('/nftRanking/nftRanking.html.twig',['nfts'=>$nfts,'clients'=>$clients,
            'nombreNfts'=>$numberNfts,'volume'=>$volume,'floorPrice'=>$floorPrice]);
    }

}
