<?php

namespace App\Controller;


use App\Entity\Chat\Conversation;
use App\Entity\Chat\GroupChat;
use App\Services\Mailer\MailerService;
use App\Entity\Chat\PrivateChat;
use App\Entity\Chat\Message;
use App\Entity\Users\User;
use App\Form\GroupType;
use App\Form\MessageType;
use App\Repository\GroupChatRepository;
use App\Repository\MessageRepository;
use App\Repository\PrivateChatRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use phpDocumentor\Reflection\DocBlock\Serializer;
use PHPUnit\Util\Xml\Validator;
use Spatie\Emoji\Emoji;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Discovery;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
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
    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }




    /**
     * @param $idGroup
     * @Route("chat/listeUsers/{idGroup?}", name="listUser")
     */
    public function AffichUser(GroupChatRepository  $repository,PrivateChatRepository $PrivateChatRepository,
                               Request $request,UserRepository $UserRepository,$idGroup)
    {
        $updateGroup=false;
        $groups = $repository->findAll();
        $users=$PrivateChatRepository->UsersContacter($this->getUser()->getId());
        // Edit group
        if($idGroup!=0)
        {
            $updateGroup=true;
            $gr= $repository->find($idGroup);
            $form = $this->createForm(GroupType::class, $gr);

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
                    ,'attr'=>['class'=>'sign__textarea'],'constraints'=>array( new count(['min'=>2]))

                ]);

            $form->add('Edite',SubmitType::class,['label'=>"Edit",
                'attr' => ['class' => 'sign__btn']]);
            $form->handleRequest($request);
            if ( $form->isSubmitted() &&  $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($gr);
                $em->flush();
                $this->addFlash('success','Group edited');
                return $this->redirectToRoute('nft');
            }
            return $this->render('Chat/chat.html.twig',
                ['form' =>  $form->createView(), 'group' => $groups,"updateGroup"=>$updateGroup,
                    'users' => $users]);
        }
        // ADD group
        else{
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
            ,'attr'=>['class'=>'sign__textarea']
            ,'constraints'=>array( new count(['min'=>2]))
        ]);
            $form->add('Create group',SubmitType::class,['label'=>"Create",
                'attr' => ['class' => 'sign__btn']]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $GoupChat->setOwner($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($GoupChat);
            $em->flush();
            $this->addFlash('success','Group created');
            return $this->redirectToRoute('nft');
        }
        return $this->render('Chat/chat.html.twig',
            ['form' => $form->createView(), 'group' => $groups,"updateGroup"=>$updateGroup,
                'users' => $users]);
        }
    }

    /**
     * @Route("chat/conversation/{id}", name="chat")
     */
    public function afficheConversation(MessageRepository $repositoryMessage,
                                        ConversationRepository $repositoryConversation,
                                        $id, PrivateChatRepository $repositoryChatPrive,
                                        Request $request): Response
    {
        $date=(new \DateTime('now'));
        $verif=false;
        /* get Conversation*/
     //  $lastidmsg= $repositoryMessage->AffichelastIdmsg($id)[0]['id'];

      // dd($lastidmsg);
        $Conversation=$repositoryConversation->find($id);
          if($Conversation instanceof GroupChat)
        {
            $verif=true;
            $c=$Conversation->getNom();
            $idgroup=$Conversation->getId();
            $membreGroup=$Conversation->getParticipants();
            $Owner=$Conversation->getOwner()->getUsername();
            $ownerId=$Conversation->getOwner()->getId();
        }
          else
        {
            $membreGroup="";
            $Owner="";
            $ownerId="";
            $idgroup="";
         $UserConversation = $repositoryChatPrive->nomPrivatChat( ($Conversation)->getId())[0];
           if($UserConversation->getSender()->getId()==$this->getUser()->getId())
            {
                $c=$UserConversation->getReceived()->getUsername();
            }else{
               $c=$UserConversation->getSender()->getUsername();
            }
        }$MessagesConversation = $repositoryMessage->AfficheMessages($Conversation);

        return $this->render('message/index.html.twig', [
            'msgConv'=>$MessagesConversation,
            'idGroup'=>$idgroup,
            'conversation'=>$Conversation,
            'c' => $c,
           //'lastidmsg'=>$lastidmsg,
            'ownerId'=>$ownerId,
            'date'=>$date,
            'membresGroup'=>$membreGroup,'owner'=>$Owner,'verif'=>$verif
        ]);
    }
   /**
     * @Route("chat/private/{id1}", name="Privatechat")
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
     * @Route ("chat/Delete/{id1}/{id}", name="deleteMessage")
     */
    function Delete( ConversationRepository $repositoryConversation,$id1,$id,MessageRepository $rep){

        $Conversation=$repositoryConversation->find($id);

        $Message=$rep->find($id1);
        $em=$this->getDoctrine()->getManager();
        $em->remove($Message);
        $em->flush();
        return $this->redirectToRoute('chat',['id'=>$Conversation->getId()]);

    }

    //!!!!!!!!!!!!!start backoffice!!!!!!!!!!!!!!!!!!!

    /**
     * @Route("admin/AdminGroups", name="AdminGroups")
     */
    public function index(PaginatorInterface $paginator,Request $request,GroupChatRepository  $repository): Response
    {
        $donnees=$repository->findAll();
        $groups = $paginator->paginate($donnees, $request->query->getInt('page', 1),2);

        return $this->render('Chat/index.html.twig',
            ['groups'=> $groups]
        );
    }
    /**

     * @Route ("admin/Delete/{id}", name="delete_Group")
     */
    function DeleteGroup( GroupChatRepository  $repository,$id){

        $u=[];
        $j=0;
        $group=$repository->find($id);
        $participants=$group->getParticipants();
        for ($i=0;$i<count($participants);$i++)
        {
            $u[$j]=$participants[$i];
            $j++;
        }$u[$j]=$group->getOwner();

        for ($i=0;$i<count($u);$i++)
        {
            $this->mailerService->sendEmailwherGroupDeleted(
                $u[$i]->getEmail(),array('nom' => $group->getNom(),'owner'=>$this->getUser()->getUsername(),"delBy"=>true,

                    'username' => $u[$i]->getUsername()
                )
            );}
        $em=$this->getDoctrine()->getManager();
        $em->remove($group);
        $em->flush();
        return $this->redirectToRoute('AdminGroups');

    }
    //!!!!!!!!!!!!!end backoffice!!!!!!!!!!!!!!!!!!!



    /**
     * @param $id2
     * @Route("chat/deleteGroupByOwner/{id2}", name="del")
     */
    public function deleteGroupByOwner($id2,GroupChatRepository  $repositoryGroup)
    {
        $u=[];
        $j=0;
        $group= $repositoryGroup->find($id2);

      $participants=$group->getParticipants();
     for ($i=0;$i<count($participants);$i++)
      {
         $u[$j]=$participants[$i];
         $j++;
      }
        for ($i=0;$i<count($u);$i++)
        {
        $this->mailerService->sendEmailwherGroupDeleted(
            $u[$i]->getEmail(),array('nom' => $group->getNom(),'owner'=>$group->getOwner()->getUsername(),"delBy"=>false,

                'username' => $u[$i]->getUsername()
            )
        );}
        $em=$this->getDoctrine()->getManager();
        $em->remove($group);
        $em->flush();
        $this->addFlash('success','Group deleted');
        return $this->redirectToRoute('nft');

    }

    /**
     * @param $id
     * @param $idp
     * @Route ("chat/DeleteMember/{idp}/{id}", name="delete_Member")
     */
    function DeleteMember( UserRepository $userRep,ConversationRepository $repositoryConversation,GroupChatRepository $repository, $idp, $id){

        $Conversation=$repositoryConversation->find($id);
        $group=$repository->find($id);
        $participants=$group->getParticipants();
        $user=$userRep->find($idp);
        $group->removeUser($user);
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('chat',['id'=>$Conversation->getId()]);
    }

    /**
     * @Route("/push", name="push")
     * @param Request $request
     */

    public function publish(PublisherInterface $publisher,Request $request): Response
    {

        if($request->request->count() > 0) {
            $em2=$this->getDoctrine()->getManager();
            $user=$em2->getRepository(User::class)->find($request->request->get('idu'));
            $datetime=$request->request->get('date');
            $conversation=$em2->getRepository(Conversation::class)->find($request->request->get('conv'));

            $Msg = new Message();

                $em = $this->getDoctrine()->getManager();
                $Msg->setSender($this->getUser());
                $Msg->setConversation($conversation);
                $Msg->setContenu($request->request->get('content'));
                $em->persist($Msg);
                $em->flush();
            $idmsg=$Msg->getId();
            }

        $update = new Update(
            'http://127.0.0.1:8000/chat/conversation/',
            json_encode(['message' => $request->request->get('content'),
                'user' => $request->request->get('idu'),
               'name'=>$this->getUser()->getUsername(),
               'date'=>$datetime,
               'idnewMsg'=>$idmsg,
               'conversation_id' => $request->request->get('conv'),
            ])
        );

        $publisher($update);

        return $this->json('Done');
    }
    /**
     * @Route("/discover", name="discover")
     */

    public function discover(Request $request, Discovery $discovery): JsonResponse
    {
        // Link: <https://hub.example.com/.well-known/mercure>; rel="mercure"
        $discovery->addLink($request);

        return $this->json([
            '@id' => '/books/1',
            'availability' => 'https://schema.org/InStock',
        ]);
    }
}


