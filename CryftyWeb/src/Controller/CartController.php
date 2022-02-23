<?php

namespace App\Controller;

use App\Repository\CartRepository;
use App\Repository\ClientRepository;
use App\Repository\NftRepository;
use App\Repository\UserRepository;
use App\Services\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/cart")
 */
class CartController extends AbstractController
{
    /*
    /**
     * @Route("/panier", name="cart_index")

    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal()
        ]);
    }*/

    /**
     * @Route("/panier", name="cart_index")
     */
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }

    /**
     * @Route("/ajouter/{id}", name="cart_add")
     */
    public function AjouterPanier($id,CartService $cartService){
        $cartService->add($id);
        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/delete/{id}", name="cart_remove")
     */
    public function remove($id, CartService $cartService)
    {
        $cartService->remove($id);
        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/addPanier/{id}",name="ajoutAuPanier")
     */
    function AjoutAuPanier($id,CartRepository $cartRep, NftRepository $nftRepo,ClientRepository $clientRepo){
        $client = $clientRepo->find($this->getUser());
        $cart = $cartRep->find($client->getCartId());
        $nft = $nftRepo->find($id);
        $cart->setNftProd([$nft]);
        $nft->setCartProd([$cart]);
        $em =$this->getDoctrine()->getManager();
        $em->flush();
        return $this->render('nft/affiche.html.twig',['id'=>$cart->getNftProd()]);
    }

    /**
     * @Route("/test/{id}" ,name="test")
     */
    function ajouterUnProduit(Request $request, $id){
        $session = $request->getSession();
        $panier = $session->get('panier',[]);
        $panier[$id]=1;
        $session->set('panier',$panier);
        return $this->render('/nft/affiche.html.twig',['id'=>$session]);
    }
}
