<?php

namespace App\Controller;
use \Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CryptoRankingController extends AbstractController
{
    private $client;
    public function __construct(HttpClientInterface $client){
        $this->client = $client;
    }

    /**
     * @Route ("rankings/list", name="rankings-list")
     * @throws TransportExceptionInterface
     */
    public function fetchAPIMarketList(PaginatorInterface $paginator, Request $request): Response
    {
        $response = $this->client->request(
            'GET',
            'https://api.coingecko.com/api/v3/coins/markets',[
                'query' => [
                    'ids' => $request->query->get('id',''),
                    'vs_currency' => 'eur',
                    'order' => $request->query->get('sort','market_cap').'_'
                        .$this->getDirection($request->query->get('direction','des')),
                    'per_page' => '250',
                    'page' => '1',
                    'sparkline' => 'false',
                ],
            ]
        );
        try {
            $content = $response->toArray();
        } catch (ClientExceptionInterface | DecodingExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
            $content = [];
        }

        $pagination = $paginator->paginate(
            $content, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            24 /*limit per page*/
        );
        dump($response->toArray());
        return $this->render("cryptoRanking/cryptoRanking.html.twig",[
            'contentArray' => $content,
            'pagination' => $pagination
        ]);
    }

    private function getDirection(string $string):string
    {

        if ($string == 'desc'){
            return substr($string,0,3);
        }
        return $string;
    }

}