<?php

namespace App\Controller;

use App\Entity\Payment\Transaction;

use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route ("transactionWallet/",name="TransactionWallet")
     */
    function AjouterTransaction(Request $request)
    {
        $transaction=new Transaction();
        $form=$this->createForm(TransactionType::class,$transaction);
        /*$form->add('payer maintenant',SubmitType::class);*/
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($transaction);
            $em->flush();
            return $this->redirectToRoute('AfficheT');
        }
        return $this->render('transaction/index.html.twig',['f'=>$form->createView()]);
    }
   /**
     * @return Response
     * @Route("afficheTransaction/",name="AfficheT")
     */
    function AfficherTransaction(TransactionRepository $repository){
        $transaction=$repository->findAll();
        return $this->render('transaction/adminTransaction.html.twig',['t'=>$transaction]);
    }

}
