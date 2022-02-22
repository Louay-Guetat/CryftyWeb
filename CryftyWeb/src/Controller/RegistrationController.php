<?php

namespace App\Controller;

use App\Entity\Users\Client;
use App\Form\RegistrationClientType;
use App\Form\UpdatePasswClType;
use App\Form\UpdateProfilType;
use App\Repository\ClientRepository;
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
    }

    /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $request)
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

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/client.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/updateclient/{id}", name="updateclient")
     * @param Request $request
     * @param ClientRepository $repository
     * @param $id
     * @param $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function updateClient(Request $request, ClientRepository $repository, $id, UserPasswordEncoderInterface $passwordEncoder)
    {
        $client=$repository->find($id);
        $form = $this->createForm(UpdateProfilType::class, $client);
        $user2 = $this->getUser();
        $password = $this->createForm(UpdatePasswClType::class, $user2);
        $form->handleRequest($request);
        $password->handleRequest($request);
        //Recuperer le nouveau mot de passe tapé par l'utilisateur
        $newpassword = $passwordEncoder->encodePassword($this->getUser(), $this->getUser()->getPassword());
        //recuperer l'ancien mot de passe dans la base de donnéees
        $oldpassword = $this->getUser()->getPassword();
        if ($newpassword = $oldpassword) {
            $this->addFlash('danger', "Ce mot de passe est dejà utilisé.");

        } else {
            $this->getUser()->setPassword($newpassword);
        }
        if ($form->isSubmitted() && $form->isValid() && $password->isSubmitted() && $password->isValid()) {
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_login');
        }
        else if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_login');
        }
        else if($password->isSubmitted() && $password->isValid()){

        }
        /*if ($password->isSubmitted() && $password->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $passwordEncoder = $this->get('security.password_encoder');

            $oldPassword = $user2->getPassword();

            // Si l'ancien mot de passe est bon

            if ($passwordEncoder->isPasswordValid($user2, $oldPassword)) {

                $newEncodedPassword = $passwordEncoder->encodePassword($user2, $user2->getPassword());

                $user2->setPassword($newEncodedPassword);



                $em->flush();

                $this->addFlash('notice', 'Votre mot de passe à bien été changé !');

                return $this->redirectToRoute('Clientlist');

            } else {

                $form->addError(new FormError('Ancien mot de passe incorrect'));

            }
        }*/

        $user = $this->security->getUser();
            if($user->getRoles() == ["ROLE_USER"] && $user2->getRoles() == ["ROLE_USER"]){
                return $this->render('registration/clientProfile.html.twig', [
                     'form' => $form->createView(),'f'=>$password->createView()
                ]);}
            else { return $this->render('registration/clientAdminUpdate.html.twig', [
                'form' => $form->createView()
            ]);}

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
    public function Listclient(ClientRepository $repository){
        $client=$repository->findAll();
        return $this->render('registration/clientlist.html.twig',['client'=>$client ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/updatepass/",name="update")
     */

    public function RestPassword(Request $request)

    {

        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $form = $this->createForm(UpdatePasswClType::class, $user);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $passwordEncoder = $this->get('security.password_encoder');

            $oldPassword = $user->getPassword();

            // Si l'ancien mot de passe est bon

            if ($passwordEncoder->isPasswordValid($user, $oldPassword)) {

                $newEncodedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());

                $user->setPassword($newEncodedPassword);



                $em->persist($user);

                $em->flush();

                $this->addFlash('notice', 'Votre mot de passe à bien été changé !');

                return $this->redirectToRoute('Clientlist');

            } else {

                $form->addError(new FormError('Ancien mot de passe incorrect'));

            }

        }



        return $this->render('registration/update.html.twig', array(

            'f' => $form->createView(),

        ));

    }
}



