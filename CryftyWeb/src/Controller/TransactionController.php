<?php

namespace App\Controller;

use App\Entity\Payment\Transaction;

use App\Form\TransactionType;
use App\Repository\CartRepository;
use App\Repository\ClientRepository;
use App\Repository\TransactionRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Knp\Component\Pager\PaginatorInterface;

class TransactionController extends AbstractController
{
    /**
     * @Route("/transaction", name="transaction")
     */
    public function index(): Response
    {
        return $this->render('transaction/index.html.twig', [
            'controller_name' => 'TransactionController',
        ]);
    }


    /**
     * @param $request
     * @Route ("transactionWallet/{id}",name="TransactionWallet")
     */
    function AjouterTransaction(Request $request,$id,CartRepository $cartRepository)
    {
        $transaction=new Transaction();
        $form=$this->createForm(TransactionType::class,$transaction);
        /*$form->add('payer maintenant',SubmitType::class);*/
        $idcart=$cartRepository->find($id);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $transaction->setCartId($idcart);
            $em->persist($transaction);
            $em->flush();
            return $this->redirectToRoute('AfficheT');
        }
        $ident=$transaction->getId();
        return $this->render('transaction/index.html.twig',['f'=>$form->createView(),'ident'=>$ident]);
    }
   /**
     * @return Response
     * @Route("afficheAdminTransaction/",name="AfficheTA")
     */
    function AfficherTransactionAdmin(PaginatorInterface $paginator,Request $request,TransactionRepository $repository,CartRepository $cartRepository){
        $donnees=$repository->findAll();
        $transaction = $paginator->paginate($donnees, $request->query->getInt('page', 1), 6);
        return $this->render('transaction/adminTransaction.html.twig',['t'=>$transaction]);
    }

    /**
     * @Route ("afficheTransaction/",name="AfficheT")
     */
    function AfficherTransaction(TransactionRepository $repository,CartRepository $cartRepository){
        $carttr=$cartRepository->find($this->getUser());
        $transaction=$repository->afficherTransaction($carttr);
        return $this->render('transaction/affiche.html.twig',['t'=>$transaction]);
    }

    /**
     * @Route ("search/",name="r")
     */
    function Search(TransactionRepository $repository,ClientRepository $client,CartRepository $cartRepo,Request $request)
    {
        $data=$request->get('rechercher');
        $tr = $repository->name($data);
        $cl = $client->findBy(['firstName'=>$tr[0]]);
        $cart = $cartRepo->findBy(['clientId'=>$cl]);
        $transaction1=$repository->findBy(['cartId'=>$cart]);
        return $this->render('transaction/adminTransaction.html.twig',['t'=>$transaction1]);
    }

    /**
     * @Route ("afficheTransactiontest",name="AfficheTest",methods={"GET"})
     */
    function AfficherTransactionTest(TransactionRepository $repository,CartRepository $cartRepository){
        $transaction=$repository->findAll();
        //return $this->render('transaction/adminTransaction.html.twig',['t'=>$transaction]);
        return $this->json($transaction,200,[],['groups'=>['cartId:read','wallets:read']]);
    }
    /**
     * @Route("AddTransactionTest", name="AddTransactionTest")
     * @Method ("POST")
     */
    public function ajouterTransactionTest(Request $request,
                                 SerializerInterface $serializer,
                                 TransactionRepository $transactionRepository)
    {
        $montant=$request->query->get("montant");
        $transaction = new Transaction();
        $transaction->setMontant($montant);
        $em = $this->getDoctrine()->getManager();
        $em->persist($transaction);
        $em->flush();

        $formatted = $serializer->normalize($transaction,200,['groups'=>['cartId:read','wallets:read']]);
        return new JsonResponse($formatted);

    }

}
