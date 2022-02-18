<?php

namespace App\Controller;

use App\Entity\Blog\BlogArticle;
use App\Form\BlogArticleType;
use App\Repository\BlogArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/AfficheBA",name="AfficheBA")
     */
    public function Affiche(BlogArticleRepository $repository){
        //$repo=$this->getDoctrine()->getRepository(BlogArticle::class);
        $BlogArticle=$repository->findAll();
        return $this->render('blog_article/Affiche.html.twig',
        ['BlogArticle'=>$BlogArticle]);
    }
    /**
     * @param $id
     * @param BlogArticleRepository $rep
     * @Route ("/Delete/{id}", name="d")
     */
    function Delete($id,BlogArticleRepository $rep){
        $BlogArticle=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($BlogArticle);
        $em->flush();
        return $this->redirectToRoute('AfficheBA');

    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/AjouterArticle", name="AjouterArticle")
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
            return $this->redirectToRoute('AfficheBA');
        }
        return $this->render('blog_article/Add.html.twig',[
            'form'=>$form->createView()
        ]);


    }

    /**
     * @Route ("/Update/{id}",name="u")
     */
    function Update(BlogArticleRepository $repository,$id,Request $request){
        $BlogArticle=$repository->find($id);
        $form=$this->createForm(BlogArticleType::class,$BlogArticle);
        $form->add('Update',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('AfficheBA');
        }
        return $this->render('blog_article/Update.html.twig',[
            'f'=>$form->createView()
        ]);


    }

    /**
     * @Route("/Search",name="search")
     */
    function Search(BlogArticleRepository $repository,Request $request){
        $data=$request->get('sear');
        $BlogArticle=$repository->findBy(['title'=>$data]);
        return $this->render('blog_article/Affiche.html.twig',[
            'BlogArticle'=>$BlogArticle
        ]);

    }

}
