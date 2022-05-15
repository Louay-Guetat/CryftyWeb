<?php

namespace App\Controller;


use App\Entity\Crypto\Wallet;
use App\Entity\Payment\Cart;
use App\Entity\Payment\Transaction;
use App\Entity\Users\Client;
use App\Entity\Users\User;
use App\Repository\BlockRepository;
use App\Repository\CartRepository;
use App\Repository\ClientRepository;
use App\Repository\NftRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use App\Services\Cart\CartService;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\NFT\Nft;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class CartController extends AbstractController
{

    /**
     * @Route("cart/panier", name="cart_index")
     */
    public function index(TransactionRepository $transactionRepository,Request $request,SessionInterface $session,NftRepository $repository,CartRepository $cartRepository,ClientRepository $client): Response
    {
        $panier = $session->get("panier", []);
        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;
        $nft=null;
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

        if($nft != null)
        {
            $nft->setCartProd([$cart]);
        }
        foreach ($dataPanier as $item){
            $total=$total+$item["produit"]->getPrice();
            //$total=0;
        }
        $n=count($dataPanier);
        $session->set('total',$total);
        $session->set('nbNft',$n);
        $tab=[];
        for($i=0;$i<count($dataPanier);$i++)
        {
            $tab[$i]=$item["produit"];
        }
        $cart->setNftProd($tab);
        $cart->setTotal($total);
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        $tr=$transactionRepository->afficherTransaction($cart);
        return $this->render('cart/index.html.twig',['dataPanier'=>$dataPanier,'cart'=>$cart,'tr'=>$tr,'tot'=>$total]);
    }



    /**
     * @Route("/panier", name="cart_index2")
     */
    public function index2(SessionInterface $session, NftRepository $nftRepository)
    {
        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;

        foreach($panier as $id => $quantite){
            $product = $nftRepository->find($id);
            $dataPanier[] = [
                "produit" => $product,
                "quantite" => $quantite
            ];
            $total += $product->getPrice() * $quantite;
        }
        $n=count($dataPanier);
        $session->set('total',$total);
        $session->set('nbNft',$n);
        return $this->render('cart/index.html.twig', compact("dataPanier", "total"));
    }


    /**
     * @Route("cart/delete/{id}", name="delete_panier")
     */
    public function SupprimerDuPanier(Nft $nft,ClientRepository $client, $id,SessionInterface $session,NftRepository $nftRepository,CartRepository $cartRepository)
    {

        $panier = $session->get("panier", []);
        $user = $this->getUser();
        $nftc = $nftRepository->SuppNft($user);
        $user= $this->getUser();
        $thisClient = $client->find($user);
        $cart = $cartRepository->find($thisClient->getCartId());

        for($i=0;$i<count($nftc);$i++)
        {
            if($nftc[$i]->getId()==$id)
            {
                if(!empty($panier[$id])) {
                    unset($panier[$id]);
                    $session->set("panier", $panier);
                    $nftc[$i]->setCartProd(null);
                    $em=$this->getDoctrine()->getManager();
                    $em->flush();
                    return $this->redirectToRoute('cart_index');
                }
                else {
                    return $this->redirectToRoute('nft');

                }


            }
        }
        return $this->render('cart/index.html.twig',['dataPanier'=>$panier,'cart'=>$cart]);
    }


    /**
     * @Route("/delete/{id}", name="delete_panier_index")
     */
    public function deleteFromPanierIndex(Nft $nft, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $nft->getId();

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index2");
    }

    /**
     * @Route("cart/ajout/{id}",name="ajouter_panier_test")
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


    /**
     * @Route("ajout/{id}",name="ajouter_panier_test2")
     */
    public function AjouterPanier2(SessionInterface $session,$id)
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
        return $this->redirectToRoute('cart_index2');
    }

    /**
     * @Route("cart/stripe/{id}", name="stripe")
     */
    public function stripe(SessionInterface $session,$id,CartRepository $cartRepository): Response
    {
        \Stripe\Stripe::setApiKey('sk_test_51IyEiPKXO6zoy45XnSBJfUeShcjGESS1F0uIZoZH3XQjKcJrBVsctduUrUqjabgjdHZVALOU1OFe4lefVdlriKJg00dp6rwSy2');
        $cart=$cartRepository->find($id);
        $amount=$cart->getTotal();
        $clientId=$cart->getClientId();
        \Stripe\Charge::create(array(
            "amount"=>$amount,
            "currency"=>"eur",
            "source"=>"tok_visa",
            "description"=>"Paiement réussie",
        ));

        $session->remove("panier");
        return $this->render('cart/Stripe.html.twig');

    }

    /**
     * @Route("/ajouterNftToCartTest/{id}", name="ajouterNftToCartTest")
     * @Method ("POST")
     */
    public function ajouterNftToCartTest($id,Request $request,
                                         SerializerInterface $serializer,
                                         CartRepository $cartRepository,
                                         NftRepository $nftRepository)
    {
        $cart = $cartRepository->find($id);
        $nftProd = $request->query->get("nftProd");
        $nftt=$nftRepository->find($nftProd);
        $nft = $this->getDoctrine()->getManager()->getRepository(Nft::class)->find($nftProd);
        $tab=[];
        for($i=0;$i<1;$i++)
        {
            $tab[$i]=$nft;
        }


        $cart->setNftProd($tab);
        $tab2=[];
        for($i=0;$i<1;$i++)
        {
            $tab2[$i]=$cart;
        }
        $nftt->setCartProd($tab2);
        $em = $this->getDoctrine()->getManager();
        $em->persist($cart);
        $em->flush();
        $formatted = $serializer->normalize($cart,200,['groups'=>['nftProd:read']]);
        return new JsonResponse($formatted);
    }


    /**
     * @Route("/afficheNftfromCartTest/{id}", name="afficheNftfromCartTest")
     * @Method ("GET")
     */
    public function afficheNftFromCartTest($id, CartRepository $cartRepository,NftRepository $nftRepository)
    {
        $tab=[];
        $i=0;
        $nft = $nftRepository->findAll();
        foreach ($nft as $oneNft)
        {
            foreach ($oneNft->getCartProd() as $cartt)
            {
                if ($cartt->getId() == $id)
                {
                    $tab[$i]=$oneNft;
                    $i++;
                }
            }
        }
        return $this->json($tab,200,[],['groups'=>['nftProd:read']]);
    }



    /**
     * @Route("/oneCart/{id}", name="oneCart")
     * @Method ("GET")
     */
    public function oneCart($id,CartRepository $cartRepository,NftRepository $nftRepository)
    {
        $cart=$cartRepository->find($id);
        return $this->json($cart,200,[],['groups'=>['cartProd:read']]);
    }


    /**
     * @Route ("/deleteNftFromCart/{id}", name="deleteNftFromCart")
     */
    function DeleteNftFromCartTest($id,NftRepository $repository, SerializerInterface $serializer,NftRepository $nftRepository){
        $nftc = $nftRepository->find($id);
        $nftc->setCartProd(null);
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        $formatted = $serializer->normalize($nftc,200,['groups'=>[]]);
        return new JsonResponse($formatted);
    }
}
