<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\NFT\Category;
use App\Entity\NFT\Nft;
use App\Entity\NFT\NftComment;
use App\Entity\NFT\SubCategory;
use App\Form\AjoutNftType;
use App\Form\CommentType;
use App\Form\ModifierNftType;
use App\Form\SearchForm;
use App\Repository\CategoryRepository;
use App\Repository\ClientRepository;
use App\Repository\NftCommentRepository;
use App\Repository\NftRepository;
use App\Repository\NodeRepository;
use App\Repository\SubCategoryRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Routing\Annotation\Route;


class NFTController extends AbstractController
{
    /**
     * @Route("/", name="nft")
     */
    public function index(NftRepository $repository, ClientRepository $clientRepo, PaginatorInterface $paginator,Request $request) {
        $nft = $repository->findAll();
        $nfts = $paginator->paginate($nft, $request->query->getInt('page',1),4);
        if($this->getUser()){
            $client = $clientRepo->find($this->getUser());
        }
        $situation =[];
        $i=0;
        foreach ($nft as $item){
            $likedBy = $item->getLikedBy();
            $j=0;
            do{
                if($likedBy[$j]!= null){
                    $situation[$i]=0;
                }
                else{
                    $situation[$i]=0;
                    $j++;
                }
            }while($j<count($likedBy)-1 && $situation[$i]!=1);
            $i++;

        }
        return $this->render('nft/index.html.twig', ['nft' => $nfts,'user'=>$this->getUser(),'situation'=>$situation]);
    }


    /**
     * @Route("nft/AfficheNft", name="AfficheNft")
     */
    function Affiche(NftRepository $repository, CategoryRepository $CatRepository,
                     ClientRepository $clientRepo,Request $request,PaginatorInterface $paginator){
        $category = $CatRepository->findAll();
        $nft = $repository->findAll();
        $data = new SearchData();
        $form = $this->createForm(SearchForm::class,$data);
        $form->handleRequest($request);
        if($this->getUser()){
            $client = $clientRepo->find($this->getUser());
        }
        else{
            $client=null;
        }
        $situation =[];
        $i=0;
        if($client==null){

        }else {
            foreach ($nft as $item) {
                $likedBy = $item->getLikedBy();
                $j = 0;
                do {
                    if ($likedBy[$j] != null) {
                        if ($client->getId() == $likedBy[$j]->getId()) {
                            $situation[$i] = 1;
                        } else
                            $situation[$i] = 0;
                        $j++;
                    } else {
                        $situation[$i] = 0;
                        $j++;
                    }
                } while ($j < count($likedBy) - 1 && $situation[$i] != 1);
                $i++;
            }
        }
        if ($form->isSubmitted() && $form->isValid()){
            //dd($data);
            $nft = $repository->findSearch($data);
            $nfts = $paginator->paginate($nft, $request->query->getInt('page',1),6);
            return $this->render('nft/afficheNft.html.twig',['nft'=>$nfts,'category'=>$category,
                                'form'=>$form->createView(),'situation'=>$situation]);
        }
        $nfts = $paginator->paginate($nft, $request->query->getInt('page',1),6);
        return $this->render('nft/afficheNft.html.twig',['nft'=>$nfts,'situation'=>$situation
                        ,'category'=>$category,'form'=>$form->createView()]);
    }

    /**
     * @Route("nft/AfficheItem/{id}", name="nftItem")
     */
    function AfficheNft($id, NftRepository $nftRepository, NftCommentRepository $Commentrepository,
                            ClientRepository $clientRepo,Request $request){
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
        $situation=0;
        if(($this->getUser()) != null){
        $client = $clientRepo->find($this->getUser()->getId());


        $i=0;
            $likedBy = $nft->getLikedBy();
            $j=0;
            do{
                if($likedBy[$j]!= null){
                    if($client->getId() == $likedBy[$j]->getId()){
                        $situation = 1;
                    }
                    else
                        $situation=0;
                    $j++;
                }
                else{
                    $situation=0;
                    $j++;
                }
            }while($j<count($likedBy)-1 && $situation[$i]!=1);
        }
        return $this->render('nft/nft.html.twig',['nftItem'=>$nft,'nftComment'=>$comments,
            'CommentForm'=>$ajoutComment->createView(),'user'=>$this->getUser(), 'situation'=>$situation]);
    }

    /**
     * @Route ("nft/updateComment/{idNft}/{idComment}" , name="updateComment")
     */
    function updateComment($idNft,$idComment, NftCommentRepository $commentRepository,Request $request,NftRepository $nftRepository){
        $nft =$nftRepository->find($idNft);
        $comments =$commentRepository->findAllByNft($nft->getId());
        $comment =$commentRepository->find($idComment);
        $commentForm = $this->createForm(CommentType::class,$comment);
        $commentForm->handleRequest($request);
        if($this->getUser() != null) {
            if(($commentForm->isSubmitted()) && $commentForm->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirectToRoute('nftItem', ['id' => $nft->getId()]);
            }
        }
        return $this->render('nft/nft.html.twig',['nftItem'=>$nft,'nftComment'=>$comments,
            'CommentForm'=>$commentForm->createView(),'user'=>$this->getUser()]);
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
        $nft->setOwner(1);
        $nft->setLikes(0);

        $formNft = $this->createForm(AjoutNftType::class,$nft);
        $formNft->add('image', FileType::class,['label'=>'e. g. Image, Audio, Video',
            'label_attr'=>['class'=>'sign__label custom-file-label'
                ,'for'=>'customFile'
                ,'mapped'=>false
                ,'required'=>true
                ,'multiple'=>false
            ]
            ,'attr'=>['class'=>'custom-file-input','name'=>'filename','id'=>'customFile','accept' => "image/*"]
            , 'constraints' => [new File([
                'maxSize' => '5120K',
            ])]
        ]);
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
        $nftForm = $this->createForm(AjoutNftType::class,$nft);
        $nftForm->add('image', TextType::class,[
            'data' => 'NFTS/'.$nft->getImage(),
            'disabled'=>true,
            'attr'=>['hidden' => true]
        ]);
        $nftForm->handleRequest($request);
        if (($nftForm->isSubmitted()) && $nftForm->isValid()) {
            $nft->setImage($nft->getImage());
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
     * @Route ("nft/deleteComment/{id}" , name="delComment")
     */
    public function deleteComment(NftCommentRepository $commRepo, $id){
        $comment = $commRepo->find($id);
        if($this->getUser()==$comment->getUser()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();
            return($this->redirectToRoute('nftItem',['id'=>$comment->getNft()->getId()]));
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
    function like($id, NftRepository $nftRepo,ClientRepository $clientRepo,Request $request){
        $nft = $nftRepo->find($id);
        $client = $clientRepo->find($this->getUser());

        $likedBy = $nft->getLikedBy();
        $situation =0;
        for($i=0;$i<count($likedBy);$i++){
            if($client->getId() == $likedBy[$i]->getId())
                $situation =1;
        }

        if ($situation == 0) {
            $client->setLikes($nft);
            $nft->setLikedBy($client);
            $nft->setLikes($nft->getLikes() + 1);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirect($request->headers->get('referer'));
        }
        else{
            $client->removeLike($nft);
            $nft->removeLikedBy($client);
            $nft->setLikes($nft->getLikes()-1);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirect($request->headers->get('referer'));
        }

    }


    /* Api Mobile */

    /**
     * @Route("/nft/AjoutNftJson", name="AjoutNftJson")
     */
    public function AjoutNftJson(Request $request,SerializerInterface $serializer, CategoryRepository $catRepo,
                                 SubCategoryRepository $subCatRepo, NodeRepository $currencyRepo, ClientRepository $clientRepo)
    {
        $nft = new Nft();
        $image = $request->query->get("image");
        $title = $request->query->get("title");
        $description = $request->query->get("description");
        $price = $request->query->get("price");
        $likes = $request->query->get("likes");
        $idCat = $request->query->get("category");
        $idSubCat = $request->query->get("subCategory");
        $idCurrency = $request->query->get("currency");
        $idClient = $request->query->get("owner");
        $category = $catRepo->find($idCat);
        $subCategory= $subCatRepo->find($idSubCat);
        $currency = $currencyRepo->find($idCurrency);
        $client = $clientRepo->find($idClient);

        $nft->setTitle($title);
        $nft->setDescription($description);
        $nft->setPrice($price);
        $nft->setImage($image);
        $nft->setLikes($likes);
        $nft->setCategory($category);
        $nft->setSubCategory($subCategory);
        $nft->setCurrency($currency);
        $nft->setOwner($client);
        $nft->setCreationDate(new \DateTime('now'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($nft);
        $em->flush();

        $formatted = $serializer->normalize($nft,200,['groups'=>['Category:read']]);

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
        return $this->json($comments,200,[],['groups'=>['commentedBy:read','comments:read']]);
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

    /**
     * @Route("nft/searchNftJson", name="searchNftJson")
     */
    function SearchJson(NftRepository $repository,Request $request){
        $title = $request->query->get("title");
        $categories[] = $request->query->get("categories");
        $subCategories[] = $request->query->get("subCategories");
        $currencies[] = $request->query->get("currencies");
        $prixMin = $request->query->get("prixMin");
        $prixMax = $request->query->get("prixMax");
        $prixOrder = $request->query->get("prixOrder");
        $likesOrder = $request->query->get("likesOrder");

        $data = new SearchData();
        if(!empty($title)){
            $data->setQ($title);
        }
        if(!empty($categories)){
            $data->setCategories($categories);
        }

        if(!empty($subCategories)){
            $data->setSubCategories($subCategories);
        }

        if(!empty($currencies)){
            $data->setCurrency($currencies);
        }

        if($prixMin != ""){
            $data->setMin((int)$prixMin);
        }
        if($prixMax != ""){
            $data->setMax((int)$prixMax);
        }
        if($prixOrder != ""){
            $data->setTriPrix($prixOrder);
        }

        if($likesOrder != ""){
            $data->setTriLikes($likesOrder);
        }

        $nft = $repository->findSearchJson($data);

        return $this->json($nft,200,[],['groups'=>['Category:read','subCategory:read','owner:read','currency:read']]);
    }

    /**
     * @Route("nft/CurrencyJson", name="CurrencyJson",methods={"GET"})
     */
    function CurrencyJson(NodeRepository $repository){
        $curr = $repository->findAll();
        return $this->json($curr,200,[],['groups'=>['curr:read']]);
    }

    /**
     * @Route("nft/ModifierNftJson/{id}", name="modifierNftJson")
     */
    public function ModifierNftJson(Request $request, $id, NftRepository $repository,
                                    CategoryRepository $catRepo, SubCategoryRepository $subCatRepo, NodeRepository $currencyRepo,
                                    SerializerInterface $serializer){
        $nft = $repository->find($id);
        $title = $request->query->get("title");
        $description = $request->query->get("description");
        $price = $request->query->get("price");
        $idCat = $request->query->get("category");
        $idSubCat = $request->query->get("subCategory");
        $idCurrency = $request->query->get("currency");
        $likes = $request->query->get("likes");

        $category = $catRepo->find($idCat);
        $subCategory= $subCatRepo->find($idSubCat);
        $currency = $currencyRepo->find($idCurrency);


        $nft->setTitle($title);
        $nft->setDescription($description);
        $nft->setPrice($price);
        $nft->setCategory($category);
        $nft->setSubCategory($subCategory);
        $nft->setCurrency($currency);
        $nft->setLikes($likes);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $formatted = $serializer->normalize($nft,200,['groups'=>[]]);
        return new JsonResponse($formatted);
    }

    /**
     * @Route ("nft/deleteNftJson/{id}", name="deleteNftJson")
     */
    function DeleteJson($id,NftRepository $repository, SerializerInterface $serializer){
        $nft=$repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($nft);
        $em->flush();
        $formatted = $serializer->normalize($nft,200,['groups'=>[]]);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("nft/AjoutNftCommentJson", name="AjoutNftCommentJson", methods={"POST"})
     */
    function AddCommentJson(Request $request,NftRepository $nftRepository, UserRepository $userRepository,
                            SerializerInterface $serializer){
        $comment = $request->query->get("comment");
        $idNft = $request->query->get("nft");
        $idClient = $request->query->get("owner");

        $nft = $nftRepository->find($idNft);
        $client = $userRepository->find($idClient);

        $nftComment = new NftComment();
        $nftComment->setComment($comment);
        $nftComment->setNft($nft);
        $nftComment->setUser($client);
        $nftComment->setLikes(0);
        $nftComment->setDislikes(0);
        $nftComment->setPostDate(new \DateTime('now'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($nftComment);
        $em->flush();

        $formatted = $serializer->normalize($nftComment,200,['groups'=>[]]);

        return new JsonResponse($formatted);
    }

    /**
     * @Route ("nft/likedJson/{id}", name="likeJson")
     */
    function likeJson($id,NftRepository $nftRepo,ClientRepository $clientRepo,Request $request, SerializerInterface $serializer){
        $idClient = $request->query->get("client");

        $nft = $nftRepo->find($id);
        $client = $clientRepo->find($idClient);

        $likedBy = $nft->getLikedBy();
        $situation =0;

        for($i=0;$i<count($likedBy);$i++){
            if($client->getId() == $likedBy[$i]->getId())
                $situation =1;
        }

        if ($situation == 0) {
            $client->setLikes($nft);
            $nft->setLikedBy($client);
            $nft->setLikes($nft->getLikes() + 1);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $formatted = $serializer->normalize($nft,200,['groups'=>[]]);
            return new JsonResponse($formatted);
        }
        else{
            $client->removeLike($nft);
            $nft->removeLikedBy($client);
            $nft->setLikes($nft->getLikes()-1);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $formatted = $serializer->normalize($nft,200,['groups'=>[]]);
            return new JsonResponse($formatted);
        }
    }
}
