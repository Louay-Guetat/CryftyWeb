<?php

namespace App\Controller;

use App\Entity\Payment\Cart;
use App\Entity\Users\Client;
use App\Form\RegistrationClientType;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
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
     */
    public function updateClient(Request $request,ClientRepository $repository,$id)
    {
        $client=$repository->find($id);
        $form = $this->createForm(RegistrationClientType::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $client->setPassword($this->passwordEncoder->encodePassword($client, $client->getPassword()));


            // Save
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/update.html.twig', [
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
    public function Listclient(ClientRepository $repository){
        $client=$repository->findAll();
        return $this->render('registration/clientlist.html.twig',['client'=>$client ]);
    }

}



