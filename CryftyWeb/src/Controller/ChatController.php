<?php

namespace App\Controller;


use App\Entity\Chat\GroupChat;

use App\Entity\Chat\PrivateChat;
use App\Entity\Chat\Message;
use App\Entity\Users\User;
use App\Form\GroupType;
use App\Form\MessageType;
use App\Repository\GroupChatRepository;
use App\Repository\MessageRepository;
use App\Repository\PrivateChatRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Serializer;
use PHPUnit\Util\Xml\Validator;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



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
     * @Route("/afficheGroups", name="afficheGroups",methods={"GET"})
     */
    public function afficheGroup(GroupChatRepository  $repository)
    {
        return $this->json($repository->findAll(),200,[],['groups'=>['post:read','owner:read']]);
    }
    /**
     * @Route("/afficheUsers", name="afficheUsers",methods={"GET"})
     */
    public function afficheUsers(UserRepository $UserRepository)
    {
        return $this->json($UserRepository->findAll(),200,[],['groups'=>['post:read','owner:read']]);
    }
    /*/**
     * @Route("/afficheUsers", name="afficheUsers",methods={"GET"})
     */
 /*   public function Users(PrivateChatRepository $PrivateChatRepository)
    {
       return $this->json ($PrivateChatRepository->UsersContacter($this->getUser()->getId()),
           200,[],
           ['groups'=>'Sender:read']);
    }*/
  /*  /**
     * @Route("/AddGroup", name="AddGroup")
     * @Method ("POST")
     */
   /*   public function addGroup(Request $request,SerializerInterface $serializer ,
                             EntityManagerInterface $em1, ValidatorInterface $validator)
    {
        $jsonRecu= $request->getContent();
        try{
            $group=$serializer->deserialize($jsonRecu,GroupChat::class,'json');
            $errors=$validator->validate($group);
            if(count($errors)>0)
            {
                return $this->json($errors,400);

            }
            $em1->persist( $group);
            $em1->flush();
            return $this->json($group,201,[],['groups'=>['post:read','owner:read']]);

        }catch(NotEncodableValueException $exception)
        {
            return $this->json(['status'=>400,'message'=>$exception->getMessage()]);
        }

    }*/
    /**
     * @Route("/AddGroup", name="AddGroup")
     * @Method ("POST")
     */
    public function ajouterGroup(Request $request,
                                 SerializerInterface $serializer,
                                 UserRepository $UserRepository)
    {



        $us=$request->query->get("participant");



      // for( $i=0;$i<Count( $us);$i++) {
            $user = $UserRepository->find($us);
        //}

        $GoupChat = new GroupChat();
        $nom=$request->query->get("nom");
        $em = $this->getDoctrine()->getManager();
        $GoupChat->setNom($nom);
        $GoupChat->setParticipants([ $user]);
        $em->persist($GoupChat);
        $em->flush();

        $formatted = $serializer->normalize($GoupChat,200,['groups'=>['post:read','owner:read']]);
        return new JsonResponse($formatted);

    }

    /**
     * @Route("/listeUsers", name="listUser")
     */
    public function AffichUser(GroupChatRepository  $repository,PrivateChatRepository $PrivateChatRepository,
                               Request $request,UserRepository $UserRepository)
    {
        $groups = $repository->findAll();
        $users=$PrivateChatRepository->UsersContacter($this->getUser()->getId());

        $GoupChat = new GroupChat();
        $form = $this->createForm(GroupType::class, $GoupChat);
        $form->add('Participants',EntityType::class,
        ['class'=>User::class,
            'query_builder'=>function(UserRepository $repUser){
                return  $repUser->createQueryBuilder('u')
                    ->where('u.id!=:id')
                    ->setParameter('id',$this->getUser()->getId())
                    ;
            },
            'choice_label'=>'username',
            'multiple'=>true,

            'label'=>"Choice your participant group",
            'label_attr'=>['class'=>'sign__label']
            ,'attr'=>['class'=>'sign__input']
            ,'constraints'=>array( new count(['min'=>3]))
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $GoupChat->setOwner($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($GoupChat);
            $em->flush();
            return $this->redirectToRoute('listUser');
        }

        return $this->render('chat/chat.html.twig',
            ['form' => $form->createView(), 'group' => $groups,
                'users' => $users]);


    }



    /**
     * @Route("/chat/{id}", name="chat")
     */
    public function afficheConversation(MessageRepository $repositoryMessage,
                                        ConversationRepository $repositoryConversation,
                                        $id, PrivateChatRepository $repositoryChatPrive,
                                        Request $request): Response
    {
        $verif=false;
        /* get Conversation*/
        $Conversation=$repositoryConversation->find($id);
          if($Conversation instanceof GroupChat)
        {
            $verif=true;
            $c=$Conversation->getNom();
          /*  for($i=0;$i<count($Conversation->getParticipants());$i++)
                {
                    $membreGroup=$Conversation->getParticipants()[$i]->getUsername();
                }*/
            $membreGroup=$Conversation->getParticipants();
            $Owner=$Conversation->getOwner()->getUsername();

        }
          else
        {

            $membreGroup="";
            $Owner="";
         $UserConversation = $repositoryChatPrive->nomPrivatChat( ($Conversation)->getId())[0];
           if($UserConversation->getSender()->getId()==$this->getUser()->getId())
            {
                $c=$UserConversation->getReceived()->getUsername();
            }else{
               $c=$UserConversation->getSender()->getUsername();
            }
        }
        /*AJOUTER MSG*/
        $Msg = new Message();
        $form1 = $this->createForm(MessageType::class, $Msg);
        $MessagesConversation = $repositoryMessage->AfficheMessages($Conversation);
        $form1->handleRequest($request);
        if ($form1->isSubmitted() && $form1->isValid()) {

            $em = $this->getDoctrine()->getManager();
           $Msg->setSender($this->getUser());
            $Msg->setConversation($Conversation);
            $em->persist($Msg);
            $em->flush();

            return$this->redirectToRoute('chat',['id'=>$Conversation->getId()]);
        }
        return $this->render('message/index.html.twig', ['msgConv'=>$MessagesConversation,
            'c' => $c,'Msg' => $form1->createView(),'membresGroup'=>$membreGroup,'owner'=>$Owner,'verif'=>$verif
        ]);
    }


   /**
     * @Route("/private/{id1}", name="Privatechat")
     */

    public function PrivateChat(UserRepository $repositoryUser,PrivateChatRepository $repositoryChatPrive,$id1)
    {
        $id2=$this->getUser();
        $received=$repositoryUser->find($id1);
        $privateChat= $repositoryChatPrive->Privatechat($id1,$id2);

       if ($privateChat)
       {

           return $this->redirectToRoute('chat',['id'=>$privateChat[0]->getId()]);
       }
        {
            $privateChat = new PrivateChat();
            $em = $this->getDoctrine()->getManager();

            $privateChat->setNom($received->getUsername());
            $privateChat->setSenderP($id2);
            $privateChat->setReceived($received);
            $em->persist($privateChat);
            $em->flush();
        //   dd($privateChat->getId());
           return $this->redirectToRoute('chat',['id'=>$privateChat->getId()]);

        }

    }

    /**
     * @param $id1
     * @param MessageRepository $rep
     * @Route ("/Delete/{id1}/{id}", name="deleteMessage")
     */
    function Delete( ConversationRepository $repositoryConversation,$id1,$id,MessageRepository $rep){

        $Conversation=$repositoryConversation->find($id);

        $Message=$rep->find($id1);
        $em=$this->getDoctrine()->getManager();
        $em->remove($Message);
        $em->flush();
        return $this->redirectToRoute('chat',['id'=>$Conversation->getId()]);

    }



}


