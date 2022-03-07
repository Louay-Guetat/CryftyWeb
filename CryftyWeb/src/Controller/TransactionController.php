<?php

namespace App\Controller;

use App\Entity\Crypto\Wallet;
use App\Entity\Payment\Transaction;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Form\TransactionType;
use App\Repository\CartRepository;
use App\Repository\ClientRepository;
use App\Repository\TransactionRepository;
use App\Repository\WalletRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Constraints\NotNull;

class TransactionController extends AbstractController
{
    /**
     * @Route("transaction/transaction1", name="transaction")
     */
    public function index(): Response
    {
        return $this->render('transaction/index.html.twig', [
            'controller_name' => 'TransactionController',
        ]);
    }


    /**
     * @param $request
     * @Route ("transaction/transactionWallet/{id}",name="TransactionWallet")
     */
    function AjouterTransaction(SessionInterface $session,Request $request,$id,CartRepository $cartRepository,ClientRepository $clientRepository,WalletRepository $walletRepository)
    {
        $transaction=new Transaction();
        $form=$this->createForm(TransactionType::class,$transaction);
        /*$form->add('payer maintenant',SubmitType::class);*/
        $form->add('wallets',EntityType::class,[
            'class'=>Wallet::class,
            'required' => false,
            'choice_label'=>'walletAddress',
            'label'=>"wallets"
            ,'label_attr'=>['class'=>'sign__label']
            ,'attr'=>['class'=>'sign__input']
            ,'constraints'=>array(new NotNull(['message'=>'ce champs est obligatoire']))
            ,'query_builder'=>function(WalletRepository $walletRepository){
                return  $walletRepository->createQueryBuilder('w')
                    ->where('w.client=:id')
                    ->setParameter('id',$this->getUser()->getId())
                    ;
            },
        ]);
        $idcart=$cartRepository->find($id);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $transaction->setCartId($idcart);
            $session->remove("panier");
            $em->persist($transaction);
            $em->flush();
            return $this->redirectToRoute('AfficheT');
        }
        $ident=$transaction->getId();
        return $this->render('transaction/index.html.twig',['f'=>$form->createView(),'ident'=>$ident]);
    }


    /**
     * @return Response
     * @Route("admin/afficheAdminTransaction/",name="AfficheTA")
     */
    function AfficherTransactionAdmin(PaginatorInterface $paginator,Request $request,TransactionRepository $repository,CartRepository $cartRepository){
        $donnees=$repository->findAll();
        $transaction = $paginator->paginate($donnees, $request->query->getInt('page', 1),3);
        return $this->render('transaction/adminTransaction.html.twig',['t'=>$transaction]);
    }

    /**
     * @Route ("transaction/afficheTransaction/",name="AfficheT")
     */
    function AfficherTransaction(PaginatorInterface $paginator,Request $request,TransactionRepository $repository,CartRepository $cartRepository){
        $carttr=$cartRepository->find($this->getUser());
        $donnees=$repository->afficherTransaction($carttr);
        $transaction=$paginator->paginate($donnees, $request->query->getInt('page', 1),3);

        return $this->render('transaction/affiche.html.twig',['t'=>$transaction]);
    }

    /**
     * @Route ("transaction/search/",name="r")
     */
    function Search(PaginatorInterface $paginator,TransactionRepository $repository,ClientRepository $client,CartRepository $cartRepo,Request $request)
    {
        $data=$request->get('rechercher');
        $tr = $repository->name($data);
        $cl = $client->findBy(['firstName'=>$tr[0]]);
        $cart = $cartRepo->findBy(['clientId'=>$cl]);
        $donnees=$repository->findBy(['cartId'=>$cart]);
        $transaction1 = $paginator->paginate($donnees, $request->query->getInt('page', 1),3);
        return $this->render('transaction/adminTransaction.html.twig',['t'=>$transaction1]);
    }

    /**
     * @Route ("transaction/afficheTransactiontest",name="AfficheTest",methods={"GET"})
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
