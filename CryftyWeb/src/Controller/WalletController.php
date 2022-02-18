<?php

namespace App\Controller;

use App\Entity\Crypto\Block;
use App\Entity\Crypto\Transfer;
use App\Entity\Crypto\Wallet;
use App\Entity\Users\Client;
use App\Form\TransferType;
use App\Form\WalletType;
use App\Repository\BlockRepository;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WalletController extends AbstractController
{
    /**
     * @Route("/wallet", name="wallet")
     */
    public function index(): Response
    {
        return $this->render('wallet/index.html.twig', [
            'controller_name' => 'WalletController',
        ]);
    }
    /**
     * @Route("/createWallet", name="create-wallet")
     */
    public function createWallet(Request $request,ClientRepository $clientRepository): Response
    {
        $wallet = new Wallet();
        $client = $clientRepository->find(1);
        $form = $this->createForm(WalletType::class,$wallet);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ){
            $em = $this->getDoctrine()->getManager();
            $wallet->setWalletAddress(bin2hex(random_bytes(17)));
            $wallet->setBalance(0);
            $wallet->setClient($client);
            $em->persist($wallet);
            $em->flush();
            return $this->redirectToRoute('view-wallets');
        }



        return $this->render('wallet/createWallet.html.twig', [
            'wallet_creation_form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/mineBlock/{walletId}", name="mine-block")
     * @throws Exception
     */
    public function mineBlock(WalletRepository $walletRepository,BlockRepository $blockRepository,int $walletId): Response
    {
        $wallet = $walletRepository->find($walletId);
        $block = new Block();
        $block->setHash(bin2hex(random_bytes(18)));
        $block->setNode($wallet->getNodeId());
        $block->setWallet($wallet);


        $prevBlock = $blockRepository->findOneBy([],['id' =>'DESC']);
        if ($prevBlock == null){
            $block->setPreviousHash('0');
        }else{
            $block->setPreviousHash($prevBlock->getHash());
        }


        $em = $this->getDoctrine()->getManager();
        $em->persist($block);
        $em->flush();

        $count = $blockRepository->countUserBlocks($wallet->getId());
        if ($count != null ) {
            $wallet->setBalance($count*6.25);
        }
        else{
            $wallet->setBalance(0);
        }
        $em->flush();



        return $this->redirectToRoute('view-wallet-info',['walletId'=>$walletId]);
    }


    /**
     * @Route("/viewWallets/{clientId}", name="view-wallets")
     * @param WalletRepository $walletRepository
     * @param ClientRepository $clientRepository
     * @param int $clientId
     * @return Response
     */
    public function viewWallets(WalletRepository $walletRepository,ClientRepository $clientRepository,int $clientId):Response
    {
        $client = $clientRepository->find($clientId);
        $wallets = $walletRepository->findBy(array('client'=> $client));
        return $this->render('wallet/viewWallets.html.twig', [
            'wallets' => $wallets,
        ]);

    }

    /**
     * @Route("/viewWalletInfo/{walletId}",name="view-wallet-info")
     * @param int $walletId
     * @param WalletRepository $walletRepository
     * @param Request $request
     * @return Response
     */
    public function viewWalletInfo(int $walletId,WalletRepository $walletRepository,Request $request):Response
    {
        $wallet = $walletRepository->find($walletId);
        $transferForm = $this->createForm(TransferType::class);
        $transferForm->handleRequest($request);
        if($transferForm->isSubmitted() && $transferForm->isValid())
        {
         $this->transferCrypto($request,$walletRepository);
        }
        return $this->render('wallet/viewWalletInfo.html.twig', [
            'wallet' => $wallet,
            'transfer_form'=>$transferForm->createView()
        ]);
    }


    /**
     * @Route("/deleteWallet/{walletId}",name="delete-wallet")
     * @param WalletRepository $walletRepository
     * @param int $walletId
     * @return Response
     */
    public function deleteWallet(WalletRepository $walletRepository,BlockRepository $blockRepository,int $walletId):Response
    {

        $walletToDelete = $walletRepository->find($walletId);
        $em = $this->getDoctrine()->getManager();
        $em->remove($walletToDelete);
        $em->flush();
        return $this->redirectToRoute("view-wallets",['clientId'=>1]);
    }


    /**
     * @Route("/updateWallet/{walletId}",name="update-wallet")
     * @param WalletRepository $walletRepository
     * @param int $walletId
     * @param Request $request
     * @param $blockRepository
     * @return Response
     */
    public function updateWallet(WalletRepository $walletRepository, int $walletId, Request $request, BlockRepository $blockRepository):Response
    {
        $walletToUpdate = $walletRepository->find($walletId);
        if (!$walletToUpdate) {
            throw $this->createNotFoundException(
                'No wallet found ,Go Back & Try Again ! '
            );
        }


        $form = $this->createForm(WalletType::class,$walletToUpdate);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $count = $blockRepository->countUserBlocks($walletToUpdate->getId());
            if ($count != null ) {
                $walletToUpdate->setBalance($count*6.25);
            }
            else{
                $walletToUpdate->setBalance(0);
            }

            $em->flush();
            return $this->redirectToRoute("view-wallet-info",["walletId"=>$walletId]);
        }

        return $this->render("wallet/updateWallet.html.twig",[
            "update_form"=>$form->createView()
        ]);
    }


    private function transferCrypto(Request $request,WalletRepository $walletRepository):void{
       $data = $request->request->get("transfer");
       $amount = $request->request->get("amount");
       $senderWallet = $walletRepository->findOneBy(array('walletAddress'=>$data['senderId']));
       $recieverWallet = $walletRepository->findOneBy(array('walletAddress'=>$data['recieverId']));
       $floatAmount= floatval($data['amount']);
       if(!$recieverWallet || !$senderWallet)
       {
           throw $this->createNotFoundException(
               "Check your Info"
           );
       }
       if($senderWallet->getBalance()>= $floatAmount)
       {
           $senderWallet->setBalance($senderWallet->getBalance() - $floatAmount );
           $recieverWallet->setBalance($recieverWallet->getBalance() + $floatAmount );
           $transfer = new Transfer();
           $transfer->setSenderId($senderWallet);
           $transfer->setRecieverId($recieverWallet);
           $transfer->setAmount($floatAmount);
           $em = $this->getDoctrine()->getManager();
           $em->persist($transfer);
           $em->flush();
       }else{
           throw $this->createNotFoundException(
               "Not Enough Crypto"
           );
       }
    }
}
