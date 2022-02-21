<?php

namespace App\Controller;

use App\Entity\NFT\Category;
use App\Entity\NFT\Nft;
use App\Entity\NFT\NftComment;
use App\Entity\NFT\SubCategory;
use App\Entity\Users\Client;
use App\Form\AjoutNftType;
use App\Form\CommentType;
use App\Form\ModifierNftType;
use App\Repository\CategoryRepository;
use App\Repository\NftCommentRepository;
use App\Repository\NftRepository;
use App\Repository\SubCategoryRepository;
use phpDocumentor\Reflection\Types\Integer;
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
    function Affiche(NftRepository $repository, CategoryRepository $CatRepository){
        $nft = $repository->findAll();
        $category = $CatRepository->findAll();
        return $this->render('nft/afficheNft.html.twig',['nft'=>$nft,'category'=>$category]);
    }

    /**
     * @Route("/AfficheItem/{id}", name="nftItem")
     */
    function AfficheNft($id, NftRepository $nftRepository, NftCommentRepository $Commentrepository, Request $request){
        $nft =$nftRepository->find($id);
        $comments =$Commentrepository->findAllByNft($nft->getId());
        $comment = new NftComment();
        $ajoutComment = $this->createForm(CommentType::class,$comment);
        $ajoutComment->handleRequest($request);
        if(($ajoutComment->isSubmitted()) && $ajoutComment->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setNft($nft);
            $comment->setPostDate(new \DateTime('now'));
            $comment->setLikes(0);
            $comment->setDislikes(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('nftItem',['id'=>$nft->getId()]);
        }
        return $this->render('nft/nft.html.twig',['nftItem'=>$nft,'nftComment'=>$comments,
            'CommentForm'=>$ajoutComment->createView()]);
    }


    /**
     * @param Request $request
     * @Route("/AjoutNft", name="AjoutNft")
     */
    public function ajoutNft(Request $request,CategoryRepository $catRepo,SubCategoryRepository $subCatRepo){
        $nft = new Nft();
        $formNft = $this->createForm(AjoutNftType::class,$nft);
        $formNft->handleRequest($request);
        if(($formNft->isSubmitted()) && $formNft->isValid()) {
            $nft->setCreationDate(new \DateTime('now'));
            $nft->setLikes(0);
            $file= $nft->getImage();
            $nft->setOwner($this->getUser());
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
            $category = $catRepo->find($nft->getCategory());
            $category->setNbrNft($category->getNbrNft()+1);
            $subCategory= $subCatRepo->find($nft->getSubCategory());
            $subCategory->setNbrNft($subCategory->getNbrNft()+1);
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

    /**
     * @Route("/searchNft", name="searchNft")
     */
    function Search(NftRepository $repository,CategoryRepository $CatRepository,Request $request){
        $category = $CatRepository->findAll();
        $donnes = $request->get('search');
        $nfts =$repository->findBy(['title'=>$donnes]);
        return $this->render('nft/afficheNft.html.twig',['nft'=>$nfts,'category'=>$category]);
    }

    /**
     * @Route("/FiltreByCat/{id}", name="filtreByCat")
     */
    function FiltreByCat($id,NftRepository $repository,CategoryRepository $CatRepository,Request $request){
        $category = $CatRepository->findAll();
        $nfts =$repository->findBy(['category'=>$id]);
        return $this->render('nft/afficheNft.html.twig',['nft'=>$nfts,'category'=>$category]);
    }
}
