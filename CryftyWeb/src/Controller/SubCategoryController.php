<?php

namespace App\Controller;

use App\Entity\NFT\Category;
use App\Entity\NFT\SubCategory;
use App\Form\AjoutSubCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubCategoryController extends AbstractController
{
    /**
     * @Route("/sub/category", name="sub_category")
     */
    public function index(): Response
    {
        return $this->render('sub_category/index.html.twig', [
            'controller_name' => 'SubCategoryController',
        ]);
    }

    /**
     * @Route("/AddSubCat", name="AjoutSubCategory")
     */
    public function AjoutSubCategory(Request $request){
        $category=new Category();
        $subCategory = new SubCategory();
        $subCategory->setCreationDate(new \DateTime('now'));
        $subCategory->setNbrNft(0);
        $formSubCat = $this->createForm(AjoutSubCategoryType::class,$subCategory);
        $formSubCat->handleRequest($request);
        if(($formSubCat->isSubmitted()) && $formSubCat->isValid()) {
            $category->setNbrNft($category->setNbrNft()+1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($subCategory);
            $em->flush();
            return $this->redirectToRoute('AjoutNft');
        }
        return $this->render('sub_category/AjoutSubCategory.html.twig',['formAjoutSubCategory'=>$formSubCat->createView()]);
    }
}
