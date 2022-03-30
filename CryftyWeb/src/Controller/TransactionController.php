<?php

namespace App\Controller;

use App\Entity\Crypto\Block;
use App\Entity\Crypto\Transfer;
use App\Entity\Crypto\Wallet;
use App\Entity\NFT\Nft;
use App\Entity\Payment\Transaction;
use App\Form\QrCodeType;
use App\Repository\BlockRepository;
use App\Repository\NftRepository;
use App\Repository\TransferRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\Form\Exception\InvalidArgumentException;
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
use App\Services\Transaction\QrcodeService;

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
    function AjouterTransaction(BlockRepository $blockRepository,SessionInterface $session,Request $request,$id,CartRepository $cartRepository,ClientRepository $clientRepository,WalletRepository $walletRepository)
    {
        $transaction=new Transaction();
        $form=$this->createForm(TransactionType::class,$transaction);
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
                    ->setParameter('id',$this->getUser()->getId());
            },
        ]);
        $idcart=$cartRepository->find($id);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $transaction->setCartId($idcart);

            $this->bLock($transaction,$cartRepository,$walletRepository,$blockRepository,$id);

            $em->persist($transaction);
            $em->flush();
            $session->get('nbNft',$session->set('nbNft',0));
            $session->remove("panier");
            return $this->redirectToRoute('AfficheT');
        }
        $ident=$transaction->getId();

        return $this->render('transaction/index.html.twig',['f'=>$form->createView(),'ident'=>$ident]);
    }

    function bLock(Transaction $transaction,CartRepository $cartRepository,WalletRepository $walletRepository
                    ,BlockRepository $blockRepository,int $id)
    {
        //jebna cart
        $idcart=$cartRepository->find($id);

        //jebna nft fel cart array
        $cartNft=$idcart->getNftProd();

        //client qui vas payer
        $buyerWallet = $transaction->getWallets();

        $em = $this->getDoctrine()->getManager();

        foreach($cartNft as $nft)
        {
            $author = $nft->getOwner();
            $nft->setOwner($this->getUser()) ;

            //wallet tezedelha flous
            $authWallet = $walletRepository->findOneBy(array('client' => $author,'isMain' => true));

            $authWallet->setBalance($authWallet->getBalance() + $nft->getPrice() );
            $buyerWallet->setBalance($buyerWallet->getBalance() - $nft->getPrice());

            $walletBlocks = $blockRepository->findBy(array('wallet'=> $buyerWallet));

            $counter = ( $nft->getPrice() / $authWallet->getNodeId()->getNodeReward() )+1;

                foreach ($walletBlocks as $block){
                    if ($counter >= 0)
                    {
                        $block->setWallet($authWallet);
                        $em->persist($block);
                        $em->persist($buyerWallet);
                    }
                    $counter--;
                }
            $nft->setCartProd(null);
                $em->flush();
        }


        $idcart->setNftProd(null);
        $em->flush();

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
        $cartTr=$cartRepository->find($this->getUser());
        $donnees=$repository->afficherTransaction($cartTr);
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
     * @Route("transaction/pdfTransaction/{id}", name="pdfTransaction")
     * @param Pdf $knpSnappyPdf
     * @return PdfResponse
     * @param Request $request
     * @param QrcodeService $qrcodeService
     */
    public function pdfTransaction(Pdf $knpSnappyPdf,QrcodeService $qrcodeService,$id, TransactionRepository $transactionRepository): PdfResponse
    {
        //pdf
        $transaction = $transactionRepository->find($id);
        $datesys= new \DateTime();

        //codeQR
        $transaction=$transactionRepository->find($id);
        $username=$transaction->getCartId()->getClientId()->getUsername();
        $adresse=$transaction->getCartId()->getClientId()->getAddress();
        $dateTransaction=$transaction->getDatetransaction()->format('d-m-Y à H:i:s');;
        $qrCode=$qrcodeService->qrcode($transaction->getId(),$username,$adresse,$dateTransaction);


        $html = $this->renderView('transaction/pdfTransaction.html.twig', [
            'tpdf'=>$transaction,'d'=>$datesys,'qrCode'=>$qrCode
        ]);
        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html),
            'file.pdf'
        );
    }

    /**
     * @Route ("afficheTransactiontest/{id}",name="AfficheTest",methods={"GET"})
     */
    function AfficherTransactionTest(TransactionRepository $repository,CartRepository $cartRepository,$id){
        //$transaction=$repository->find($id);
        //return $this->render('transaction/adminTransaction.html.twig',['t'=>$transaction]);
        $cartTr=$cartRepository->find($id);
        $transaction=$repository->afficherTransaction($cartTr);
        return $this->json($transaction,200,[],['groups'=>['cartId:read','wallets:read']]);
    }
    /**
     * @Route("AddTransactionTest", name="AddTransactionTest")
     * @Method ("POST")
     */
    public function ajouterTransactionTest(Request $request,
                                           SerializerInterface $serializer,
                                           TransactionRepository $transactionRepository,BlockRepository $blockRepository,
                                           CartRepository $cartRepository,WalletRepository $walletRepository)
    {
        $adresseWallet=$request->query->get("wallets");
        $cartId=$request->query->get("cartId");
        $transaction = new Transaction();
        $transaction->setWallets($this->getDoctrine()->getManager()->getRepository(Wallet::class)->find($adresseWallet));
        $transaction->setCartId($this->getDoctrine()->getManager()->getRepository(Cart::class)->find($cartId));
        $em = $this->getDoctrine()->getManager();
        $this->bLock($transaction,$cartRepository,$walletRepository,$blockRepository,$cartId);
        $em->persist($transaction);
        $em->flush();
        $formatted = $serializer->normalize($transaction,200,['groups'=>['wallets:read','cartId:read']]);
        return new JsonResponse($formatted);

    }

    /**
     * @Route ("afficheTransactionWalletTest",name="AfficheTestWallet",methods={"GET"})
     */
    public function afficheTransactionWalletTest(Request $request,
                                                 SerializerInterface $serializer,
                                                 TransactionRepository $transactionRepository,WalletRepository $walletRepository)
    {
        $idClient=$request->query->get("clientWallet");
        $w=$walletRepository->WalletClient($idClient);
        return $this->json($w,200,[],['groups'=>['wallets:read']]);
    }
}
