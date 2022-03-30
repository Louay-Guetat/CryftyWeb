<?php

namespace App\Controller;

use App\Entity\Crypto\Node;
use App\Entity\Crypto\Transfer;
use App\Entity\Crypto\Wallet;
use App\Entity\Users\Client;
use App\Entity\Users\User;
use App\Form\WalletType;
use App\Repository\BlockRepository;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use Exception;
use Liip\ImagineBundle\Exception\Config\Filter\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/wallet")
 */
class JsonWalletController extends AbstractController
{
    /**
     * @Route("/all/{id}", name="json-wallet-all",methods={"GET"})
     */
    public function wallets(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();

        try{
        $client = $em->getRepository(User::class)->find($id);
        $wallets = $em->getRepository(Wallet::class)->findBy(array('client'=>$client));
        return $this->json($wallets,200,[],['groups'=>['apiwallets:read']]);
        }
        catch (Exception $exception){
            return new JsonResponse("Exception",400);

        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @Route("/info/{id}",name="json-wallet-info")
     */
    public function walletInfo(int $id):JsonResponse{
        $em = $this->getDoctrine()->getManager();
        $wallets = $em->getRepository(Wallet::class)->find($id);
        return $this->json($wallets,200,[],['groups'=>'apiwallets:read']);
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws Exception
     * @Route("/add",name="json-wallet-add")
     */
    public function createWallet(Request $request,SerializerInterface $serializer):JsonResponse{
         $em = $this->getDoctrine()->getManager();

         //get from request
         $wallet = new Wallet();
         $clientId = $request->get("client");
         $label = $request->get("label");
         $image = $request->files->get("image");
         $client = $em->getRepository(Client::class)->find($clientId);

         $node = $em->getRepository(Node::class)->findOneBy([],['id'=>"DESC"]);
         //Set Values
         $wallet->setNodeId($node);
         $wallet->setWalletLabel($label);
         $wallet->setClient($client);
         $wallet->setWalletAddress(bin2hex(random_bytes(17)));
         $wallet->setBalance(0);
         $wallet->setClient($client);
         $wallet->setIsActive(false);

        if ($image) {
            $safeFilename = bin2hex(random_bytes(16));
            $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
            try {
                $image->move(
                    $this->getParameter('wallet_image_directory'),
                    $newFilename
                );
            } catch (FileException $e) {

            }
        }else{
            $newFilename = "defaultImage.png";
        }


        $wallet->setWalletImageFileName($newFilename);

        $walletCount = $em->getRepository(Wallet::class)->findAll();
        if (!$walletCount){
            $wallet->setIsMain(true);
        }else{
            $wallet->setIsMain(false);
        }

        $em->persist($wallet);
        $em->flush();
        $data = $serializer->serialize($wallet,'json',['groups'=>'apiwallets:write']);
        return new JsonResponse($data,200);
    }

    /**
     * @param Request $request
     * @param int $id
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @Route("/update/{id}",name="json-wallet-update")
     */
    public function updateWallet(Request $request,int $id,SerializerInterface $serializer):JsonResponse{
        $em = $this->getDoctrine()->getManager();
        $label = $request->get("label");
        $wallet = $em->getRepository(Wallet::class)->find($id);
        $wallet->setWalletLabel($label);
        $em->flush();
        $data = $serializer->serialize($wallet,'json',['groups'=>'apiwallets:write']);
        return new JsonResponse($data,200);
    }

    /**
     * @Route("/delete/{id}",name="json-wallet-delete")
     * @param int $id
     * @return JsonResponse
     */
    public function deleteWallet(int $id): JsonResponse
    {
        try{
        $wallet = $this->getDoctrine()->getRepository(Wallet::class)->find($id);
        $this->getDoctrine()->getManager()->remove($wallet);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse("Wallet with id : {$id} has been deleted",200);
        }
        catch (Exception $exception){
            return new JsonResponse("No wallet with this id found ",400);
        }
    }

    /**
     * @param Request $request
     * @param WalletRepository $walletRepository
     * @param BlockRepository $blockRepository
     * @return JsonResponse
     * @Route ("/transfer",name="json-wallet-transfer")
     */
    public function transferBalance(Request $request,WalletRepository $walletRepository, BlockRepository $blockRepository): JsonResponse
    {
        $from = $request->query->get("from");
        $to = $request->query->get("to");
        $amount = $request->query->get("amount");
        $senderWallet = $walletRepository->findOneBy(array('walletAddress'=>$from));
        $recieverWallet = $walletRepository->findOneBy(array('walletAddress'=>$to));
        $floatAmount= floatval($amount);
        if(!$recieverWallet || !$senderWallet)
        {
            throw $this->createNotFoundException(
                "Check your Info "
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

            $counter = ($amount/$senderWallet->getNodeId()->getNodeReward())+1;

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
            return $this->json("Transfer  to {$transfer->getRecieverId()->getWalletLabel()} completed");

        }else{
            throw $this->createNotFoundException(
                "Not Enough Crypto"
            );
        }
    }

    /**
     * @Route("/upload-image/{id}",name="json-wallet-uplaod")
     * @param int $id
     * @return JsonResponse
     */
    public function uploadImage(int $id,Request $request): JsonResponse
    {
        try{
            $wallet = $this->getDoctrine()->getRepository(Wallet::class)->find($id);
            $imageFile = $request->files->get("file");
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
                $wallet->setWalletImageFileName($newFilename);
            }
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse("Updated Image",200);
        }
        catch (Exception $exception){
            return new JsonResponse("Server Error Occurred ",400);
        }
    }

}
