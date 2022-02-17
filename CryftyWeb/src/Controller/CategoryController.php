<?php

namespace App\Controller;

use App\Entity\NFT\Category;
use App\Form\AjoutCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    /**
     * @Route("/AddCat", name="AjoutCategory")
     */
    public function AjoutCategory(Request $request){
        $category = new Category();
        $category->setCreationDate(new \DateTime('now'));
        $category->setNbrNft(0);
        $formCat = $this->createForm(AjoutCategoryType::class,$category);
        $formCat->handleRequest($request);
        if(($formCat->isSubmitted()) && $formCat->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('nft');
        }
        return $this->render('category/AjoutCategory.html.twig',['formAjoutCategory'=>$formCat->createView()]);
    }
}
