<?php

namespace App\Controller;

use App\Entity\Blog\BlogArticle;
use App\Entity\Blog\BlogComment;
use App\Repository\BlogCommentRepository;
use App\Form\BlogArticleType;
use App\Form\CommentbType;
use App\Repository\BlogArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Knp\Component\Pager\PaginatorInterface;

class BlogArticleController extends AbstractController
{
    /**
     * @Route("/blog/article", name="blog_article")
     */
    public function index(): Response
    {
        return $this->render('blog_article/index.html.twig', [
            'controller_name' => 'BlogArticleController',
        ]);
    }

    /**
     * @param BlogArticleRepository $repository
     * @return Response
     * @Route("blog/AfficheBA",name="AfficheBA")
     */
    public function Affiche(BlogArticleRepository $repository,Request $request, PaginatorInterface $paginator){
        $donnees = $this->getDoctrine()->getRepository(BlogArticle::class)->findBy([],['date' => 'desc']);
        $articles = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            2 // Nombre de résultats par page
        );
        $BlogArticle=$repository->findAll();
        return $this->render('blog_article/Affiche.html.twig',
            ['BlogArticle'=>$articles]);
    }

    /**
     * @param BlogArticleRepository $repository
     * @return Response
     * @Route ("blog/AfficheBAAD",name="AfficheBAAD")
     */
    public function AfficheAd(BlogArticleRepository $repository){
        //$repo=$this->getDoctrine()->getRepository(BlogArticle::class);
        $BlogArticle=$repository->findAll();
        return $this->render('blog_article/AfficheAD.html.twig',
            ['BlogArticle'=>$BlogArticle]);
    }


    /**
     * @param Request $request
     * @param  BlogArticleRepository $BARepository
     * @param BlogCommentRepository $Commentrepository
     * @Route("blog/AfficheBItem/{id}", name="articleItem")
     */
    function AfficheAr($id, BlogArticleRepository $BARepository, BlogCommentRepository $Commentrepository, Request $request){
        $BA =$BARepository->find($id);
        $commentb =$Commentrepository->findAllBYBA($BA->getId());
        $comment = new BlogComment();
        $ajoutComment = $this->createForm(CommentbType::class,$comment);
        $ajoutComment->handleRequest($request);
        if(($ajoutComment->isSubmitted()) && $ajoutComment->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setarticle($BA);
            $comment->setPostDate(new \DateTime('now'));
            $comment->setLikes(0);
            $comment->setDislikes(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('articleItem',['id'=>$BA->getId()]);
        }
        return $this->render('blog_article/AfficheAr.html.twig',['articleItem'=>$BA,'BlogComment'=>$commentb,
            'CommentbForm'=>$ajoutComment->createView()]);
    }
    /**
     * @param $id
     * @param BlogArticleRepository $rep
     * @Route ("blog/Delete/{id}", name="d")
     */
    function Delete($id,BlogArticleRepository $rep){
        $BlogArticle=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($BlogArticle);
        $em->flush();
        return $this->redirectToRoute('AfficheBAAD');

    }
    /**
     * @Route("blog/DeleteC/{id}",name="dc")
     */
    function DeleteC($id,BlogCommentRepository $repC){
        $comment=$repC->find($id);
        if($this->getUser()==$comment->getUser()){
            $em=$this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();}
        return $this->redirectToRoute('AfficheBA');

    }

    /**
     * @param Request $request
     * @return Response
     * @Route("blog/AjouterArticle", name="AjouterArticle")
     */
    function Add(Request $request){
        $BlogArticle=new BlogArticle();
        $form=$this->createForm(BlogArticleType::class,$BlogArticle);
        $form->handleRequest($request);
        //$form->add('Add',SubmitType::class);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($BlogArticle);
            $em->flush();
            return $this->redirectToRoute('AfficheBAAD');
        }
        return $this->render('blog_article/Add.html.twig',[
            'form'=>$form->createView()
        ]);


    }

    /**
     * @Route ("blog/Update/{id}",name="u")
     */
    function Update(BlogArticleRepository $repository,$id,Request $request){
        $BlogArticle=$repository->find($id);
        $form=$this->createForm(BlogArticleType::class,$BlogArticle);
        $form->add('Update',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('AfficheBAAD');
        }
        return $this->render('blog_article/Update.html.twig',[
            'f'=>$form->createView()
        ]);


    }

    /**
     * @Route("blog/Search",name="search")
     */
    function Search(BlogArticleRepository $repository,Request $request){
        $data=$request->get('sear');
        $BlogArticle=$repository->findBy(['title'=>$data]);
        return $this->render('blog_article/Affiche.html.twig',[
            'BlogArticle'=>$BlogArticle
        ]);

    }
    //-----------Partie mobile-------------
    /******************Ajouter Reclamation*****************************************/
    /**
     * @param Request $request
     * @Route("/addArticle", name="add_article")
     * @Method("POST")
     */

    public function ajouterArticle(Request $request)
    {
        $article=new BlogArticle();
        $title = $request->query->get("title");
        $category = $request->query->get("category");
        $author = $request->query->get("author");
        $contents = $request->query->get("contents");
        $em = $this->getDoctrine()->getManager();
        $date = new \DateTime('now');

        $article->setTitle($title);
        $article->setCategory($category);
        $article->setContents($contents);
        $article->setAuthor($author);
        $article->setDate($date);

        $em->persist($article);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($article);
        return new JsonResponse($formatted);

    }

    /******************Supprimer Reclamation*****************************************/

    /**
     * @param Request $request
     * @Route("/deleteReclamation", name="delete_reclamation")
     * @Method("DELETE")
     */

    public function deleteArticle(Request $request) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(BlogArticle::class)->find($id);
        if($article!=null ) {
            $em->remove($article);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Reclamation a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id reclamation invalide.");


    }

    /******************Modifier Reclamation*****************************************/
    /**
     * @param Request $request
     * @Route("/updateReclamation", name="update_reclamation")
     * @Method("PUT")
     */
    public function modifierArticle(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $article = $this->getDoctrine()->getManager()
            ->getRepository(BlogArticle::class)
            ->find($request->get("id"));

        $article->setTitle($request->get("title"));
        $article->setCategory($request->get("category"));
        $article->setContents($request->get("contents"));
        $article->setAuthor($request->get("author"));
        $article->setImage($request->get("image"));

        $em->persist($article);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($article);
        return new JsonResponse("Reclamation a ete modifiee avec success.");

    }



    /******************affichage Reclamation*****************************************/

    /**
     * @Route("/displayReclamations", name="display_reclamation")
     */
    public function allRecAction()
    {

        $reclamation = $this->getDoctrine()->getManager()->getRepository(Reclamation::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reclamation);

        return new JsonResponse($formatted);

    }
    /**
     *@Route("/listarticle", name="list_article")
     */
    public function allResAction(NormalizerInterface $normalizer)
    {

        $repository = $this->getDoctrine()->getRepository(BlogArticle::class);
        $article =$repository->findAll();
        $jsonContent = $normalizer->normalize($article, 'json',['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));

    }

}

