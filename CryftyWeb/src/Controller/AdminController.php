<?php

namespace App\Controller;

use App\Entity\Users\Admin;
use App\Form\RegistrationAdminType;
use App\Form\UpdateAdminType;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/admin/registeradmin", name="registeradmin")
     */
    public function registerAdmin(Request $request)
    {
        $user = new Admin();

        $form = $this->createForm(RegistrationAdminType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_ADMIN']);
            $user->setIsActive(true);

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('adminlist');
        }

        return $this->render('admin/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/updateadmin/{id}", name="updateadmin")
     */
    public function updateAdmin(Request $request,AdminRepository $repository,$id)
    {
        $admin=$repository->find($id);
        $form = $this->createForm(UpdateAdminType::class, $admin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $admin->setPassword($this->passwordEncoder->encodePassword($admin, $admin->getPassword()));


            // Save
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute('adminlist');
        }

        return $this->render('admin/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/delete/{id}", name="delete_admin")
     */
    public function deleteadmin($id,AdminRepository $repository) {

        $user=$repository->find($id);;
        $em=$this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('adminlist');
    }


    /**
     * @return Response
     * @Route ("/admin/Adminlist",name="adminlist")
     */
    public function Listclient(AdminRepository $repository,Request $request,PaginatorInterface $paginator){
        $donnees=$repository->findAll();
        $admin = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );
        return $this->render('admin/adminlist.html.twig',['admin'=>$admin ]);
    }

    /**
     * @Route("/Admin/{id}", name="show_admin")
     */
    public function ShowAdmin(int $id,AdminRepository $repository)
    {
        $admin =$repository->find($id);

        return $this->render("admin/showadmin.html.twig", [
            "m" => $admin,
        ]);
    }



}
