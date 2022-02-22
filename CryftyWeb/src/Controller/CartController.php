<?php

namespace App\Controller;

use App\Entity\Payment\Cart;
use App\Entity\Users\Client;
use App\Entity\Users\User;
use App\Repository\CartRepository;
use App\Repository\ClientRepository;
use App\Repository\NftRepository;
use App\Repository\UserRepository;
use App\Services\Cart\CartService;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\NFT\Nft;


class CartController extends AbstractController
{

    /**
     * @Route("/panier", name="cart_index")
     */
    public function index(Request $request,SessionInterface $session,NftRepository $repository,CartRepository $cartRepository,ClientRepository $client): Response
    {
        $panier = $session->get("panier", []);
        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;

        foreach($panier as $id => $quantite){
            $nft = $repository->find($id);
            $dataPanier[] = [
                "produit" => $nft,
                "quantite"=>$quantite
            ];
        }
        $user= $this->getUser();
        $thisClient = $client->find($user);
        $cart = $cartRepository->find($thisClient->getCartId());
        $nft->setCartProd([$cart]);
        $tab=[];
        foreach ($dataPanier as $item){
            $total=$total+$item["produit"]->getPrice();
        }
        for($i=0;$i<count($dataPanier);$i++)
        {
            $tab[$i]=$item["produit"];
        }
        $cart->setNftProd($tab);

        $em=$this->getDoctrine()->getManager();
        $em->flush();
        //dd($total);
        return $this->render('cart/index.html.twig',['dataPanier'=>$dataPanier]);
        }

    /**
     * @Route("/delete/{id}", name="delete_panier")
     */
    public function SupprimerDuPanier(Nft $nft, SessionInterface $session,$id,NftRepository $nftRepository)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $nft->getId();

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);
        $nft=$nftRepository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($nft);
        $em->flush();
        return $this->redirectToRoute("cart_index");
    }


    /**
     * @Route("/cart/ajout/{id}",name="ajouter_panier_test")
     */
    public function AjouterPanier(SessionInterface $session,$id)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }
        // On sauvegarde dans la session
        $session->set("panier", $panier);
        return $this->redirectToRoute('cart_index');
    }
}
