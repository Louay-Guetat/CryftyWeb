<?php

namespace App\Controller;

use App\Entity\Payment\Cart;
use App\Entity\Users\Client;
use App\Entity\Users\User;
use App\Form\RegistrationClientType;
use App\Form\UpdatePasswClType;
use App\Form\UpdateProfilType;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class RegistrationController extends AbstractController
{
    private $passwordEncoder;
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security,UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->security = $security;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $request,UserRepository $repository)
    {
        $user = new Client();

        $form = $this->createForm(RegistrationClientType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_USER']);

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $cart=new Cart();
            $cart->setClientId($user);
            $em->persist($cart);
            $em->flush();
            return $this->redirectToRoute('app_login');
        }
        return $this->render('registration/client.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/client/delete/{id}", name="delete_client")
     */
    public function deleteclient($id,ClientRepository $repository) {

        $user=$repository->find($id);;
        $em=$this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('Clientlist');
    }


    /**
     * @return Response
     * @Route ("/Clientlist/",name="Clientlist")
     */
    public function Listclient(ClientRepository $repository,Request $request,PaginatorInterface $paginator): Response
    {
        $donnees=$repository->findAll();
        $client = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );
        return $this->render('registration/clientlist.html.twig',['client'=>$client ]);
    }


    /**
     * @param Request $request
     * @param UserRepository $repository
     * @return Response
     * @throws Exception
     * @Route("/resetPassword/",name="reset-password")
     */
    public function resetPassword(Request $request,UserRepository $repository):Response
    {

        $em = $this->getDoctrine()->getManager();

        /** @var User $user */
        $user = $this->security->getUser();


        dump($user);



            $oldPasswordFromForm = $request->request->get("oldPassword");





            if ($this->passwordEncoder->isPasswordValid($user, $oldPasswordFromForm)) {
                $newPasswordFromForm = $request->request->get("password");
                $newEncodedPassword = $this->passwordEncoder->encodePassword($user, $newPasswordFromForm);
                $updatedUser = $repository->find($user->getId());
                $updatedUser->setPassword($newEncodedPassword);



                $em->persist($user);

                $em->flush();

                $this->addFlash('notice', 'Votre mot de passe à bien été changé !');

                return $this->redirectToRoute("client-profil");
            }

                throw new Exception();



        }

    /**
     * @Route("/profile",name="client-profil")
     * @return Response
     */
    public function profileInfo(ClientRepository $clientRepository,Request $request):Response{

        /** @var User $user */
        $loggedUser = $this->security->getUser();

        $userToUpdate = $clientRepository->findOneBy(array("username" => $loggedUser->getUsername()));
        $updateForm = $this->createForm(UpdateProfilType::class,$userToUpdate);
        $updateForm->handleRequest($request);
        if($updateForm->isSubmitted() && $updateForm->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }

        return $this->render('registration/clientProfile.html.twig',[
            'form' => $updateForm->createView()
        ]);
        }


    /**
     * @Route("/Client/{id}", name="show_client")
     */
    public function ShowClient(int $id,ClientRepository $repository)
    {
        $Client =$repository->find($id);

        return $this->render("registration/showclient.html.twig", [
            "C" => $Client,
        ]);
    }
}



