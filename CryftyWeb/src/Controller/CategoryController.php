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
     * @Route("/category/index", name="category")
     */
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    /**
     * @Route("/category/AfficheCat",name="showCat")
     */
    function afficheCategory(CategoryRepository $categoryRepository, SubCategoryRepository  $subCategoryRepository){
        $category = $categoryRepository->findAll();
        $subCategory = $subCategoryRepository->findAll();
        return $this->render('category/consulterCategory.html.twig',['Category'=>$category,'subCategory'=>$subCategory]);
    }

    /**
     * @Route("/category/AddCat", name="AjoutCategory")
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

    /**
     * @Route("/category/deleteCategory/{id}" , name="DeleteCat")
     */
    function DeleteCategory($id , CategoryRepository $categoryRepo){
        $category =$categoryRepo->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('showCat');
    }

    /**
     * @Route("/category/deleteSubCategory/{id}" , name="DeleteSubCat")
     */
    function DeleteSubCategory($id , SubCategoryRepository $SubcategoryRepo){
        $Subcategory =$SubcategoryRepo->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($Subcategory);
        $em->flush();
        return $this->redirectToRoute('showCat');
    }

    /**
     * @Route("/category/ModifierCat/{id}", name="ModifCat")
     */
    function ModifierCategory(Request $request, $id, CategoryRepository $Categoryrepo){
        $category =$Categoryrepo->find($id);
        $categoryForm = $this->createForm(AjoutCategoryType::class,$category);
        $categoryForm->handleRequest($request);
        if(($categoryForm->isSubmitted()) && $categoryForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('showCat');
        }
        return $this->render('category/UpdateCategory.html.twig',['formModifierCategory'=>$categoryForm->createView()]);
    }

    /**
     * @Route("/category/ModifierSubCat/{id}", name="ModifSubCat")
     */
    function ModifierSubCategory(Request $request, $id, SubCategoryRepository $SubCategoryrepo,CategoryRepository $categoryRepo){
        $Subcategory =$SubCategoryrepo->find($id);
        $oldCat = $categoryRepo->find($Subcategory->getCategory());
        $SubcategoryForm = $this->createForm(AjoutSubCategoryType::class,$Subcategory);
        $SubcategoryForm->handleRequest($request);
            if (($SubcategoryForm->isSubmitted()) && $SubcategoryForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $Subcategory =$SubCategoryrepo->find($id);
                if($oldCat == $Subcategory->getCategory()) {
                    return $this->redirectToRoute('showCat');
                }
                else{
                    $oldCat->setNbrSubCategory($oldCat->getNbrSubCategory()-1);
                    $newCategory = $categoryRepo->find($Subcategory->getCategory());
                    $newCategory->setNbrSubCategory($newCategory->getNbrSubCategory()+1);
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                    return $this->redirectToRoute('showCat');
                }
            }
        return $this->render('category/UpdateCategory.html.twig',['formModifierCategory'=>$SubcategoryForm->createView()]);
    }

    /**
     * @Route("/category/searchCategory", name="searchCat")
     */
    function SearchCat(CategoryRepository $repository,SubCategoryRepository  $subCategoryRepository,Request $request){
        $donnes = $request->get('searchCat');
        $category = $repository->findBy(['name'=>$donnes]);
        $subCategory = $subCategoryRepository->findAll();
        return $this->render('category/consulterCategory.html.twig',['Category'=>$category,'subCategory'=>$subCategory]);
    }

    /**
     * @Route("/category/searchSubCategory", name="searchSubCat")
     */
    function SearchSubCat(CategoryRepository $CategoryRepository,SubCategoryRepository $subCategoryRepository,Request $request){
        $donnes = $request->get('searchSubCat');
        $Subcategory =$subCategoryRepository->findBy(['name'=>$donnes]);
        $Category = $CategoryRepository->findAll();
        return $this->render('category/consulterCategory.html.twig',['Category'=>$Category,'subCategory'=>$Subcategory]);
    }

}
