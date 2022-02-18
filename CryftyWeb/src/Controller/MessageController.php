<?php

namespace App\Controller;

use App\Entity\Chat\GroupChat;
use App\Entity\Chat\Message;
use App\Form\GroupType;
use App\Form\MessageType;
use App\Repository\GroupChatRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message")
     */
    public function index(): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }
    /*CREATE GROUPE*/
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("/affiche2",name="a2")
     */

    function Ajouter(Request $request,MessageRepository $repository){
        $Msg=new Message();
        $form=$this->createForm(MessageType::class,$Msg);
        $form->add('Sender',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em=$this->getDoctrine()->getManager();
            $em->persist($Msg);
            $em->flush();
            return $this->redirectToRoute('Msg');
        }

        return $this->render('message/index.html.twig',
            ['Msg'=>$form->createView()]);

    }
}
