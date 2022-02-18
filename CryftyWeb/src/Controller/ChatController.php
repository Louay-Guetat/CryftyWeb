<?php

namespace App\Controller;


use App\Entity\Chat\GroupChat;


use App\Form\GroupType;
use App\Repository\GroupChatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Request;

class ChatController extends AbstractController
{
    /**
     * @Route("/chat", name="chat")
     */
    public function index(): Response
    {
        return $this->render('chat/chat.html.twig', [
            'controller_name' => 'ChatController',
        ]);
    }

    /**
     * @Route("/affiche", name="a1")
     */
    public function AffichUser(ConversationRepository $repository,Request $request)
    {
        $user=$repository->findAll();
        dump($user);
        $GoupChat=new GroupChat();
        $form=$this->createForm(GroupType::class,$GoupChat);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em=$this->getDoctrine()->getManager();
            $em->persist($GoupChat);
            $em->flush();
            return $this->redirectToRoute('a');
        }

        return $this->render('chat/chat.html.twig',
            ['form'=>$form->createView(),  'user'=>$user]);


    }

    /*public function rechercheUser(UserRepository $repository,Request $request)
    {




      if($request->isMethod("POST"))
      {
          $username=$request->get('search');
          $user=$repository->findBy(['username'=>$username]);
      }
        return $this->render('chat/chat.html.twig',
            [
                'user'=>$user
            ]);
    }*/







    /*CREATE GROUPE*/
   /**
      * @param Request $request
      * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
      * @Route ("/affiche1",name="a2")
      */

    function Ajouter(Request $request,GroupChatRepository $repository){


    }
}
