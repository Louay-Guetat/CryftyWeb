<?php

namespace App\Controller;

use App\Entity\NFT\Nft;
use App\Form\AjoutNftType;
use App\Form\ModifierNftType;
use App\Repository\NftRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NFTController extends AbstractController
{
    /**
     * @Route("/nft", name="nft")
     */
    public function index() {
        return $this->render('nft/index.html.twig', [
            'controller_name' => 'NFTController',
        ]);
    }

    function Affiche(NftRepository $repository ){
        $nft = $repository->findAll();
        return $this->render('/index.html.twig',['c'=>$nft]);
    }


    /**
     * @param Request $request
     * @Route("/AjoutNft", name="AjoutNft")
     */
    public function ajoutNft(Request $request){
        $nft = new Nft();
        $nft->setCreationDate(new \DateTime('now'));
        $formNft = $this->createForm(AjoutNftType::class,$nft);
        $formNft->add('Ajouter',SubmitType::class);
        $formNft->handleRequest($request);
        if(($formNft->isSubmitted()) && $formNft->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($nft);
            $em->flush();
            return $this->redirectToRoute('nft');
        }
        return $this->render('nft/ajoutNft.html.twig',['formAjoutNft'=>$formNft->createView()]);
    }

    /**
     * @Route("/ModifierNft/{id}", name="modifierNft")
     */
    public function ModifierNft(Request $request, $id, NftRepository $repository){
        $nft =$repository->find($id);
        $nftForm = $this->createForm(ModifierNftType::class,$nft);
        $nftForm->add('Modifier',SubmitType::class);
        $nftForm->handleRequest($request);
        if(($nftForm->isSubmitted()) && $nftForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('nft');
        }
        return $this->render('nft/modifierNft.html.twig',['formModifierNft'=>$nftForm->createView()]);
    }

    /**
     * @Route ("/deleteNft/{id}", name="deleteNft")
     */
    function Delete($id,NftRepository $repository){
        $nft=$repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($nft);
        $em->flush();
        return($this->redirectToRoute('nft'));
    }



}
