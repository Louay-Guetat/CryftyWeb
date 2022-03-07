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
use App\Repository\ClientRepository;
use App\Repository\NftCommentRepository;
use App\Repository\NftRepository;
use App\Repository\SubCategoryRepository;
use phpDocumentor\Reflection\Types\Integer;
use PhpParser\Node\Scalar\String_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

class NFTController extends AbstractController
{
    /**
     * @Route("/", name="nft")
     */
    public function index(NftRepository $repository ) {
        $nft = $repository->findAll();
        return $this->render('nft/index.html.twig', ['nft' => $nft,'user'=>$this->getUser()]);
    }

    /**
     * @Route("nft/AfficheNft", name="AfficheNft")
     */
    function Affiche(NftRepository $repository, CategoryRepository $CatRepository){
        $nft = $repository->findAll();
        $category = $CatRepository->findAll();
        return $this->render('nft/afficheNft.html.twig',['nft'=>$nft,'category'=>$category]);
    }

    /**
     * @Route("nft/AfficheItem/{id}", name="nftItem")
     */
    function AfficheNft($id, NftRepository $nftRepository, NftCommentRepository $Commentrepository, Request $request){
        $nft =$nftRepository->find($id);
        $comments =$Commentrepository->findAllByNft($nft->getId());
        $comment = new NftComment();
        $ajoutComment = $this->createForm(CommentType::class,$comment);
        $ajoutComment->handleRequest($request);
        if($this->getUser() != null) {
            if (($ajoutComment->isSubmitted()) && $ajoutComment->isValid()) {
                $comment->setUser($this->getUser());
                $comment->setNft($nft);
                $comment->setPostDate(new \DateTime('now'));
                $comment->setLikes(0);
                $comment->setDislikes(0);
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();
                return $this->redirectToRoute('nftItem', ['id' => $nft->getId()]);
            }
        }
        return $this->render('nft/nft.html.twig',['nftItem'=>$nft,'nftComment'=>$comments,
            'CommentForm'=>$ajoutComment->createView(),'user'=>$this->getUser()]);
    }

    /**
     * @param Request $request
     * @Route("nft/AjoutNft", name="AjoutNft")
     */
    public function ajoutNft(Request $request,CategoryRepository $catRepo,SubCategoryRepository $subCatRepo){
        $nft = new Nft();

        $category = new Category();
        $subCategory = new SubCategory();
        $nft->setCreationDate(new \DateTime('now'));
        $nft->setOwner($this->getUser());
        $nft->setLikes(0);

        $formNft = $this->createForm(AjoutNftType::class,$nft);
        $formNft->handleRequest($request);
            if ($formNft->isSubmitted() && $formNft->isValid()) {
                $nft->setCreationDate(new \DateTime('now'));
                $nft->setLikes(0);
                $file = $nft->getImage();
                $nft->setOwner($this->getUser());
                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                    try {
                        $file->move($this->getParameter('images_directory'), $fileName);
                    } catch (FileException $e) {
                        $e->getMessage();
                    }
                    $nft->setImage($fileName);
                    $category = $catRepo->find($nft->getCategory());
                    $category->setNbrNft($category->getNbrNft() + 1);
                    $subCategory = $subCatRepo->find($nft->getSubCategory());
                    $subCategory->setNbrNft($subCategory->getNbrNft() + 1);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($nft);
                    $em->flush();
                    return $this->redirectToRoute('nft');
                }
        return $this->render('nft/ajoutNft.html.twig',['formAjoutNft'=>$formNft->createView()]);
    }

    /**
     * @Route("nft/ModifierNft/{id}", name="modifierNft")
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
     * @Route ("nft/deleteNft/{id}", name="deleteNft")
     */
    function Delete($id,NftRepository $repository){
        $nft=$repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($nft);
        $em->flush();
        return($this->redirectToRoute('nft'));
    }

    /**
     * @Route("nft/searchNft", name="searchNft")
     */
    function Search(NftRepository $repository,CategoryRepository $CatRepository,Request $request){
        $category = $CatRepository->findAll();
        $donnes = $request->get('search');
        $nfts =$repository->findBy(['title'=>$donnes]);
        return $this->render('nft/afficheNft.html.twig',['nft'=>$nfts,'category'=>$category]);
    }

    /**
     * @Route("nft/FiltreByCat/{id}", name="filtreByCat")
     */
    function FiltreByCat($id,NftRepository $repository,CategoryRepository $CatRepository,Request $request){
        $category = $CatRepository->findAll();
        $nfts =$repository->findBy(['category'=>$id]);
        return $this->render('nft/afficheNft.html.twig',['nft'=>$nfts,'category'=>$category]);
    }

    /**
     * @Route ("nft/deleteComment/{id}" , name="delComment")
     */
    public function deleteComment(NftCommentRepository $commRepo, $id){
        $comment = $commRepo->find($id);
        if($this->getUser()==$comment->getUser()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();
            return($this->redirectToRoute('nftItem'));
        }
    }

    /**
     * @Route("nft/AfficheProfile/{id}", name="profil")
     */
    function afficheProfil($id,NftRepository $nftRepository){
        $nfts = $nftRepository->findBy(['owner'=>$id]);
        return $this->render('/nft/profile.html.twig',['nfts'=>$nfts]);
    }

    /**
     * @Route ("nft/liked/{id}", name="like")
     */
    function like($id, NftRepository $nftRepo,ClientRepository $clientRepo){
        $nft = $nftRepo->find($id);
        $client = $clientRepo->find($this->getUser());
        $likedBy = $nft->getLikedBy();
        $situation =0;
        for($i=0;$i<count($likedBy);$i++){
            if($client == $likedBy[$i])
                $situation =1;
        }

        if ($situation == 0) {
            $client->setLikes($nft);
            $nft->setLikedBy($client);
            $nft->setLikes($nft->getLikes() + 1);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('nft');
        }
        else{
            $client->removeLike($nft);
            $nft->removeLikedBy($client);
            $nft->setLikes($nft->getLikes()-1);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('nft');
        }
    }

    /**
     * @Route ("nft/error" , name="error")
     */
    function Error(){
        return $this->render();
    }

    /* Api Mobile */

    /**
     * @Route("/nft/AjoutNftJson", name="AjoutNftJson")
     * @Method=("POST")
     */
    public function AjoutNftJson(Request $request,SerializerInterface $serializer)
    {
        $nft = new Nft();
        $title = $request->query->get("title");
        $description = $request->query->get("description");
        $price = $request->query->get("price");
        $creationDate = $request->query->get("creationDate");
        $image = $request->query->get("image");
        $likes = $request->query->get("likes");

        $em = $this->getDoctrine()->getManager();
        $nft->setTitle($title);
        $nft->setDescription($description);
        $nft->setPrice($price);
        $file = $nft->getImage();
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        try {
            $file->move($this->getParameter('images_directory'), $fileName);
        } catch (FileException $e) {
            $e->getMessage();
        }
        $nft->setImage($fileName);
        $nft->setLikes($likes);
        $nft->setCreationDate(new \DateTime('now'));
        $em->persist($nft);
        $em->flush();

        $formatted = $serializer->normalize($nft);

        return new JsonResponse($formatted);
    }

    /**
     * @Route("nft/AfficheItemJson/{id}", name="nftItemJson", methods={"GET"})
     */
    function AfficheNftJson($id, NftRepository $nftRepository){
        $nft =$nftRepository->find($id);
        return $this->json($nft,200,[],['groups'=>['Category:read','subCategory:read','owner:read','currency:read']]);
    }

    /**
     * @Route("nft/AfficheCommentsJson/{id}", name="nftCommentsJson", methods={"GET"})
     */
    function AfficheCommentsJson($id, NftCommentRepository $Commentrepository){
        $comments =$Commentrepository->findAllByNft($id);
        return $this->json($comments,200,[],['groups'=>['user:read','comments:read']]);
    }

    /**
     * @Route("nft/AfficheProfileJson/{id}", name="profilJson")
     */
    function afficheProfilJson($id,NftRepository $nftRepository){
        $nfts = $nftRepository->findBy(['owner'=>$id]);
        return $this->json($nfts,200,[],['groups'=>['Category:read','subCategory:read','owner:read','currency:read']]);
    }

    /**
     * @Route("nft/AfficheNftJson", name="AfficheNftJson",methods={"GET"})
     */
    function AfficheJson(NftRepository $repository){
        $nft = $repository->findAll();
        return $this->json($nft,200,[],['groups'=>['Category:read','subCategory:read','owner:read','currency:read']]);
    }

}
