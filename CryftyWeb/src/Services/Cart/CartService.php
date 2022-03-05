<?php
namespace App\Services\Cart;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\NftRepository;

class CartService {
    protected $session;
    protected $NftRepository;
    public function __construct(SessionInterface $session, NftRepository $NftRepository){
        $this->session=$session;
        $this->NftRepository=$NftRepository;
    }
    public function add(int $id) {
        $cartTab=new cart();
        $panier=$this->session->get('panier', $cartTab);
        if(!empty($panier[$id])){
            $panier[$id]++;
        }else {
            $panier[$id]=1;
        }

        $this->session->set('panier', $panier);
    }
    public function remove(int $id) {
        $panier=$this->session->get('panier', []);
        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        $this->session->set('panier', $panier);
    }
    public function getFullCart() : array {
        $panier=$this->session->get('panier', []);
        $panierWithData=[];
        foreach($panier as $id => $quantity) {
            $panierWithData[]=[
                'product'=>$this->nftRepository->find($id),
                'quantity'=>$quantity
            ];
        }
        return $panierWithData;
    }
    public function getTotal() : float {
        $total=0;

        foreach($this->getFullCart() as $item){
            $total+=$item['product']->getPrix() * $item['quantity'];
        }
        return $total;
    }
}