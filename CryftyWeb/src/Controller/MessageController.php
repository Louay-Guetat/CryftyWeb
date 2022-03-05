<?php

namespace App\Controller;

use App\Entity\Chat\GroupChat;
use App\Entity\Chat\Message;
use App\Form\GroupType;
use App\Form\MessageType;
use App\Repository\ConversationRepository;
use App\Repository\GroupChatRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
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

    /**
     * @Route("/afficheGroup/", name="m")
     */
    public function groupe(MessageRepository $repository)
    {
        $groups = $repository-> AfficheMessages(2);

        return $this->render('chat/index.html.twig',
            [ 'msgs' => $groups]);
    }
}
