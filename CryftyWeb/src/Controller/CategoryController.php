<?php

namespace App\Controller;

use App\Entity\NFT\Category;
use App\Entity\NFT\SubCategory;
use App\Form\AjoutCategoryType;
use App\Form\AjoutSubCategoryType;
use App\Repository\CategoryRepository;
use App\Repository\SubCategoryRepository;
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
    public function AjoutCategory(Request $request, CategoryRepository $CatRepo, SubCategoryRepository $subCatRepo){
        $category = new Category();
        $formCat = $this->createForm(AjoutCategoryType::class,$category);
        $formCat->handleRequest($request);
        if(($formCat->isSubmitted()) && $formCat->isValid()) {
            $category->setCreationDate(new \DateTime('now'));
            $category->setNbrNft(0);
            $category->setNbrSubCategory(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('AjoutCategory');
        }
        $subCategory = new SubCategory();
        $subCategory->setCreationDate(new \DateTime('now'));
        $subCategory->setNbrNft(0);
        $formSubCat = $this->createForm(AjoutSubCategoryType::class,$subCategory);
        $formSubCat->handleRequest($request);
        if(($formSubCat->isSubmitted()) && $formSubCat->isValid()) {
            $cat = $CatRepo->find($subCategory->getCategory());
            $cat->setNbrSubCategory($cat->getNbrSubCategory()+1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($subCategory);
            $em->flush();
            return $this->redirectToRoute('AjoutCategory');
        }
        return $this->render('category/AjoutCategory.html.twig',['formAjoutCategory'=>$formCat->createView()
        ,'formAjoutSubCategory'=>$formSubCat->createView()]);
    }
}
