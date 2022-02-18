<?php

namespace App\Controller;

use App\Services\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
