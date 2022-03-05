<?php

namespace App\Controller;

use App\Services\Mailer\MailerService;
use \Symfony\Component\Form\Exception\InvalidArgumentException;
use App\Entity\Crypto\Block;
use App\Entity\Crypto\Transfer;
use App\Entity\Crypto\Wallet;
use App\Entity\Users\Client;
use App\Entity\Users\User;
use App\Form\TransferType;
use App\Form\WalletType;
use App\Repository\BlockRepository;
use App\Repository\ClientRepository;
use App\Repository\TransferRepository;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;



class WalletController extends AbstractController
{
    private $security;
    private $mailerService;
    public function __construct(Security $security,MailerService $mailerService){
        $this->security = $security;
        $this->mailerService = $mailerService;
         }
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
     * @Route("/wallet/createWallet", name="create-wallet")
     * @throws Exception
     */
    public function createWallet(Request $request,ClientRepository $clientRepository): Response
    {
        $wallet = new Wallet();
        $client = $this->security->getUser();
        $form = $this->createForm(WalletType::class,$wallet);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ){
            $em = $this->getDoctrine()->getManager();
            $imageFile = $form->get('image')->getData();


            if ($imageFile) {
                $safeFilename = bin2hex(random_bytes(16));
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('wallet_image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
            }else{
                $newFilename = "defaultImage.png";
            }


                $wallet->setWalletImageFileName($newFilename);



            $wallet->setWalletAddress(bin2hex(random_bytes(17)));
            $wallet->setBalance(0);
            $wallet->setClient($client);
            $wallet->setIsActive(false);
            $em->persist($wallet);
            $em->flush();
            $this->mailerService->sendWalletVerificationEmail(
               $client->getEmail(),array('walletLabel' => $wallet->getWalletLabel(),
                   'walletId' => $wallet->getId(),
                   'username' => $client->getUsername()
               )
            );
            $this->addFlash('success','Wallet "'.$wallet->getWalletLabel().'" Created . Good job ,
             Check your Email and Activate it! ');
            return $this->redirectToRoute('view-wallets');
        }



        return $this->render('wallet/createWallet.html.twig', [
            'wallet_creation_form' => $form->createView(),
        ]);
    }


    /**
     * @Route("wallet/mineBlock/{walletId}", name="mine-block")
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
            $wallet->setBalance($count* $wallet->getNodeId()->getNodeReward());
        }
        else{
            $wallet->setBalance(0);
        }
        $em->flush();



        return $this->redirectToRoute('view-wallet-info',['walletId'=>$walletId]);
    }


    /**
     * @Route("wallet/viewWallets/", name="view-wallets")
     * @param WalletRepository $walletRepository
     * @param ClientRepository $clientRepository
     * @return Response
     */
    public function viewWallets(WalletRepository $walletRepository,ClientRepository $clientRepository):Response
    {

        $currentUser = $this->security->getUser();

        $client = $clientRepository->find($currentUser->getId());
        $wallets = $walletRepository->findBy(array('client'=> $client));
        return $this->render('wallet/viewWallets.html.twig', [
            'wallets' => $wallets,
        ]);

    }

    /**
     * @Route("wallet/viewWalletInfo/{walletId}",name="view-wallet-info")
     * @param int $walletId
     * @param WalletRepository $walletRepository
     * @param Request $request
     * @return Response
     */
    public function viewWalletInfo(int $walletId,WalletRepository $walletRepository,TransferRepository $transferRepository,BlockRepository $blockRepository,Request $request):Response
    {
        $wallet = $walletRepository->find($walletId);
        $this->denyAccessUnlessGranted('access', $wallet);

        $transferForm = $this->createForm(TransferType::class);
        $transferForm->handleRequest($request);
        if($transferForm->isSubmitted() && $transferForm->isValid())
        {
         $this->transferCrypto($request,$walletRepository,$blockRepository);
        }

        $transferOutArray = $transferRepository->findBy(array('senderId'=>$walletId));
        $transferInArray = $transferRepository->findBy(array('recieverId'=>$walletId));
        return $this->render('wallet/viewWalletInfo.html.twig', [
            'wallet' => $wallet,
            'transfer_form'=>$transferForm->createView(),
            'outTransfers'=>$transferOutArray,
            'inTransfers'=>$transferInArray
        ]);
    }

    /**
     * @Route("wallet/activateWallet/{id}",name="activate-wallet")
     * @param int $id
     * @param WalletRepository $walletRepository
     * @return RedirectResponse
     */
    public function activateWallet(int $id,WalletRepository $walletRepository): RedirectResponse
    {
        $walletToActivate = $walletRepository->find($id);
        $walletToActivate->setIsActive(true);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('view-wallets');
    }
    /**
     * @Route("wallet/resendEmail/{id}",name="resend-wallet-email")
     * @param int $id
     * @param WalletRepository $walletRepository
     * @return RedirectResponse
     */
    public function resendEmail(int $id,WalletRepository $walletRepository): RedirectResponse
    {
        $wallet = $walletRepository->find($id);
        $client = $this->security->getUser();

        $this->mailerService->sendWalletVerificationEmail(
            $client->getEmail(),array('walletLabel' => $wallet->getWalletLabel(),
                'walletId' => $wallet->getId(),
                'username' => $client->getUsername()
            )
        );
        $this->addFlash('success','Verification Email Resent ! Check again');

        return $this->redirectToRoute('view-wallets');
    }


    /**
     * @Route("wallet/deleteWallet/{walletId}",name="delete-wallet")
     * @param TransferRepository $transferRepository
     * @param WalletRepository $walletRepository
     * @param BlockRepository $blockRepository
     * @param int $walletId
     * @return Response
     */
    public function deleteWallet(TransferRepository $transferRepository,WalletRepository $walletRepository,BlockRepository $blockRepository,int $walletId):Response
    {

        $walletToDelete = $walletRepository->find($walletId);
        $this->denyAccessUnlessGranted('access',$walletToDelete);
        $em = $this->getDoctrine()->getManager();
        $em->remove($walletToDelete);
        $em->flush();
        return $this->redirectToRoute("view-wallets");
    }


    /**
     * @Route("wallet/updateWallet/{walletId}",name="update-wallet")
     * @param WalletRepository $walletRepository
     * @param int $walletId
     * @param Request $request
     * @param BlockRepository $blockRepository
     * @return Response
     * @throws Exception
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
            $imageFile = $form->get('image')->getData();


            if ($imageFile) {

                $safeFilename = bin2hex(random_bytes(16));
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('wallet_image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $walletToUpdate->setWalletImageFileName($newFilename);
            }





            $em = $this->getDoctrine()->getManager();
            $count = $blockRepository->countUserBlocks($walletToUpdate->getId());
            if ($count != null) {

                $walletToUpdate->setBalance($count* $walletToUpdate->getNodeId()->getNodeReward());

            }
            else{
                $walletToUpdate->setBalance(0);
            }

            $em->flush();

            $this->addFlash('success','Wallet Updated ! ');
            return $this->redirectToRoute("view-wallet-info",["walletId"=>$walletId]);
        }


        return $this->render("wallet/updateWallet.html.twig",[
            "update_form"=>$form->createView()
        ]);
    }


    private function transferCrypto(Request $request,WalletRepository $walletRepository,BlockRepository $blockRepository):void{
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
       if($recieverWallet === $senderWallet){
           throw new InvalidArgumentException(
               "Vous ne pouvez pas envoyée du crypto a vous-même"
           );
       }
       if($senderWallet->getBalance()>= $floatAmount)
       {
           $em = $this->getDoctrine()->getManager();

           $senderWallet->setBalance($senderWallet->getBalance() - $floatAmount );
           $recieverWallet->setBalance($recieverWallet->getBalance() + $floatAmount );

           $walletBlocks = $blockRepository->findBy(array('wallet'=>$senderWallet));
           $counter = ($amount/6.25)+1;
           foreach ($walletBlocks as $block){
               if ($counter >= 0)
               {
               $block->setWallet($recieverWallet);
               $em->persist($block);
               }
               $counter--;
           }

           $transfer = new Transfer();
           $transfer->setSenderId($senderWallet);
           $transfer->setRecieverId($recieverWallet);
           $transfer->setAmount($floatAmount);

           $em->persist($transfer);
           $em->flush();
       }else{
           throw $this->createNotFoundException(
               "Not Enough Crypto"
           );
       }
    }
}
