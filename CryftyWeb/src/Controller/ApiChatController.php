<?php

namespace App\Controller;

use App\Entity\Chat\GroupChat;
use App\Entity\Chat\Message;
use App\Entity\Chat\PrivateChat;
use App\Entity\Users\User;
use App\Repository\ConversationRepository;
use App\Repository\GroupChatRepository;
use App\Repository\MessageRepository;
use App\Repository\PrivateChatRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
class ApiChatController extends AbstractController
{
    /**
     * @Route("/afficheGroups/{iduser}", name="afficheGroups")
     * @Method ("POST")
     */
    public function afficheGroup(GroupChatRepository  $repository,UserRepository $userRepository,$iduser)
    {
        $tab= [];
        $i=0;
        $Groups=$repository->findAll();
        $User=$userRepository->find($iduser);
        foreach ($Groups as $group)
        {
            foreach ($group->getParticipants() as $participant )
            {
                if($participant->getId() ==$iduser) {
                    $tab[$i] = $group;
                    $i++;
                }
            }if($group->getOwner()->getId()==$iduser){
            $tab[$i] = $group;
            $i++;
        }
        }
        return $this->json($tab,200,[],['groups'=>['participants:read','ownerGroup:read']]);
    }
    /**
     * @Route("/afficheMemberGroup/{idGroup}", name="afficheMemberGroup")
     * @Method ("POST")
     */
    public function afficheMembersParGroupe(GroupChatRepository  $repository,$idGroup)
    {
        $tab= [];
        $i=0;
        $Group=$repository->find($idGroup);
        foreach ($Group->getParticipants() as $participant )
        {
            $tab[$i]= $participant;
            $i++;
        }
        //$tab[$i] = $Group->getOwner();
        return $this->json($tab,200,[],['groups'=>['participants:read','ownerGroup:read']]);
    }
    /**
     * @Route("afficheOwnerGroup/{idGroup}", name="afficheOwnerGroup")
     * @Method ("POST")
     */
    public function afficheOwnerParGroupe(GroupChatRepository  $repository,$idGroup)
    {
        $Group=$repository->find($idGroup);
        return $this->json($Group->getOwner(),200,[],['groups'=>['ownerGroup:read']]);
    }
    /**
     * @Route("/afficheUsers/{idOwner}", name="afficheUsers")
     * @Method ("POST")
     */
    public function afficheUsers(UserRepository $UserRepository,$idOwner)
    {
        return $this->json($UserRepository->findusersMinusOwner($idOwner),200,[],['groups'=>['participants:read','ownerGroup:read']]);
    }

    /**
     * @Route("AddGroup", name="AddGroup")
     * @Method ("POST")
     */
    public function ajouterGroup(Request $request,
                                 SerializerInterface $serializer,
                                 UserRepository $UserRepository)
    {
        $TabUser=[];
        $GoupChat = new GroupChat();
        $em = $this->getDoctrine()->getManager();

        $nom=$request->query->get("nom");
        $GoupChat->setNom($nom);
        $usr[] = $request->query->get("participant");
        $u=[',','[',']'];
        $str = str_replace($u,' ',$usr[0],$count);
        // dd($str);
        $pieces=explode(" ",$str );
        for ($i =1;$i<=$count-1;$i++)
        {
            $user[$i] = $UserRepository->find($pieces[$i]);
            $TabUser[$i]=$user[$i];
        }
        $GoupChat->setParticipants($TabUser);
        $Owner=$request->query->get("owner");
        $GoupChat->setOwner($UserRepository->find($Owner));

        $em->persist($GoupChat);
        $em->flush();

        $formatted = $serializer->normalize($GoupChat,200,['groups'=>['participants:read','ownerGroup:read']]);
        return new JsonResponse($formatted);

    }


    /**
     * @Route("UpdateGroup/{idGroup}", name="UpdateGroup")
     * @Method ("POST")
     */
    public function UpdateGroup(Request $request,$idGroup,GroupChatRepository  $repository,
                                SerializerInterface $serializer,
                                UserRepository $UserRepository)
    {
        $TabUser=[];
        $GoupChat =$repository->find($idGroup);
        $em = $this->getDoctrine()->getManager();
        $nom=$request->query->get("nom");

        $GoupChat->setParticipants(null);
        $em->flush();
        $GoupChat->setNom($nom);
        $usr[] = $request->query->get("participant");
        $u=[',','[',']'];
        $str = str_replace($u,' ',$usr[0],$count);

        $pieces=explode(" ",$str );
        for ($i =1;$i<=$count-1;$i++)
        {
            $user[$i] = $UserRepository->find($pieces[$i]);
            $TabUser[$i]=$user[$i];
        }
        $GoupChat->setParticipants($TabUser);
        $em->flush();

        $formatted = $serializer->normalize($GoupChat,200,['groups'=>['participants:read','ownerGroup:read']]);
        return new JsonResponse($formatted);

    }

    /**
     * @Route("/private/{id1}", name="PrivatechatApi")
     */

    public function PrivateChat(Request $request,
                                SerializerInterface $serializer,UserRepository $repositoryUser,PrivateChatRepository $repositoryChatPrive,$id1)
    {
        $id=$request->query->get("CurrentUser");
        $id2=$repositoryUser->find($id);
        $received=$repositoryUser->find($id1);
        $privateChat= $repositoryChatPrive->Privatechat($id1,$id2);


        $formatted = $serializer->normalize($privateChat,200,['groups'=>['PrivateChat:read']]);
        return new JsonResponse($formatted);


    }



    /**
     * @Route("/Addprivate/{id1}", name="AddPrivatechatApi")
     */

    public function AddPrivateChat(Request $request,
                                   SerializerInterface $serializer,UserRepository $repositoryUser,PrivateChatRepository $repositoryChatPrive,$id1)
    {
        $id=$request->query->get("CurrentUser");
        $id2=$repositoryUser->find($id);
        $received=$repositoryUser->find($id1);

        {
            $privateChat = new PrivateChat();
            $em = $this->getDoctrine()->getManager();

            $privateChat->setNom($received->getUsername());
            $privateChat->setSenderP($id2);
            $privateChat->setReceived($received);
            $em->persist($privateChat);
            $em->flush();
            $formatted = $serializer->normalize($privateChat,200,['groups'=>['PrivateChat:read']]);
            return new JsonResponse($formatted);

        }

    }




    /**
     * @Route("/affichelastMsg/{idConv}", name="affichelastMsg")
     * @Method ("POST")
     */
    public function afficheLastMsg($idConv,MessageRepository  $repository,ConversationRepository $Conversationrepository)
    {
        $c=$Conversationrepository->find($idConv);
        return $this->json($repository->AffichelastMsg($c),200,[],
            ['groups'=>['participants:read','ownerGroup:read']]);
    }

    /**
     * @Route("/afficheMsg/{idConv}", name="afficheMsg")
     * @Method ("POST")
     */
    public function afficheMsg($idConv,MessageRepository  $repository,ConversationRepository $Conversationrepository)
    {
        $c=$Conversationrepository->find($idConv);
        //  dd($repository->AfficheMessages($c));
        return $this->json($repository->AfficheMessages($c),200,[],['groups'=>['msg:read']]);
    }
    /**
     * @Route("/deleteMsg/{idMsg}", name="deleteMsg")
     * @Method ("POST")
     */
    public function DeleteMsg($idMsg,MessageRepository  $repository)
    {
        $message=$repository->find($idMsg);
        $em=$this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();
        return $this->json("message deleted",200,[],['groups'=>['msg:read']]);
    }


    /**
     * @Route("/deleteGrp/{idgr}", name="deleteGrp")
     * @Method ("POST")
     */
    public function DeleteGroup($idgr,GroupChatRepository  $Grouprepository)
    {
        $message=$Grouprepository->find($idgr);
        $em=$this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();
        return $this->json("group deleted",200,[],['groups'=>['msg:read']]);
    }
    //UsersContacter
    /**
     * @Route("/afficheuserContacter/{iduser}", name="afficheuserContacter")
     * @Method ("POST")
     */
    public function afficheuserContacter($iduser,PrivateChatRepository  $repository,ConversationRepository $Conversationrepository)
    {
        return $this->json($repository->UsersContacter($iduser),200,[],['groups'=>['PrivateChat:read']]);
    }
    /**
     * @Route("sendmsg/{idConv}/{idSender}", name="SendMsg")
     * @Method ("POST")
     */
    public function SendMsg(Request $request,$idConv,$idSender,
                            SerializerInterface $serializer,UserRepository $userRepository,
                            ConversationRepository $ConvRepository)
    {
        $Message = new Message();
        $em = $this->getDoctrine()->getManager();

        $contenu=$request->query->get("contenu");
        $Message ->setContenu($contenu);

        $conv=$ConvRepository->find($idConv);
        $Message->setConversation($conv);

        $user=$userRepository->find($idSender);
        $Message->setSender($user);

        $em->persist($Message);
        $em->flush();

        $formatted = $serializer->normalize($Message,200,['groups'=>['msg:read']]);
        return new JsonResponse($formatted);

    }
}