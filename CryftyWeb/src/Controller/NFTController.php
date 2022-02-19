<?php

namespace App\Controller;

use App\Entity\NFT\Category;
use App\Entity\NFT\Nft;
use App\Entity\NFT\SubCategory;
use App\Entity\Users\Client;
use App\Form\AjoutNftType;
use App\Form\ModifierNftType;
use App\Repository\NftRepository;
use PhpParser\Node\Scalar\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NFTController extends AbstractController
{
    /**
     * @Route("/nft", name="nft")
     */
    public function index(NftRepository $repository ) {
        $nft = $repository->findAll();
        return $this->render('nft/index.html.twig', ['nft' => $nft]);
    }

    /**
     * @Route("/AfficheNft", name="AfficheNft")
     */
    function Affiche(NftRepository $repository ){
        $nft = $repository->findAll();
        return $this->render('nft/afficheNft.html.twig',['nft'=>$nft]);
    }

    /**
     * @Route("/AfficheItem/{id}", name="nftItem")
     */
    function AfficheNft($id, NftRepository $repository){
        $nft =$repository->find($id);
        return $this->render('nft/nft.html.twig',['nftItem'=>$nft]);
    }


    /**
     * @param Request $request
     * @Route("/AjoutNft", name="AjoutNft")
     */
    public function ajoutNft(Request $request){
        $nft = new Nft();
        $Client = new Client();
        $category = new Category();
        $subCategory = new SubCategory();
        $nft->setCreationDate(new \DateTime('now'));
        $nft->setLikes(0);
        $formNft = $this->createForm(AjoutNftType::class,$nft);
        $formNft->handleRequest($request);
        if(($formNft->isSubmitted()) && $formNft->isValid()) {
            $category->setNbrNft($category->getNbrNft()+1);
            $subCategory->setNbrNft($subCategory->getNbrNft()+1);
            $file= $nft->getImage();
            $fileName= md5(uniqid()).'.'.$file->guessExtension();
            try{
                $file->move($this->getParameter('images_directory'),$fileName);
            }catch(FileException $e) {
                $e->getMessage();
            }
            $em = $this->getDoctrine()->getManager();
            $nft->setImage($fileName);
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
