<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Users\Client;
use App\Entity\Users\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use function Symfony\Component\VarDumper\Dumper\esc;


class UtilisateurApiController extends AbstractController
{
    /**
     * @Route("/utilisateur/api", name="utilisateur_api")
     */
    public function index(): Response
    {
        return $this->render('utilisateur_api/index.html.twig', [
            'controller_name' => 'UtilisateurApiController',
        ]);
    }


    /**
     * @Route("client/signup",name="app_register")
     */
    public function signupAction(Request $request,UserPasswordEncoderInterface $passwordEncoder){

        $email = $request->query->get("email");
        $username = $request->query->get("username");
        $password = $request->query->get("password");
        $roles = $request->query->get("roles");
        $firstName = $request->query->get("firstName");
        $lastName = $request->query->get("lastName");
        $phoneNumber = $request->query->get("phoneNumber");
        $age = $request->query->get("age");

        //control email
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            return new Response("email invaild");
        }
        $client = new Client();
        $client->setUsername($username);
        $client->setEmail($email);
        $client->setPassword($passwordEncoder->encodePassword($client,$password));
        $client->setFirstName($firstName);
        $client->setLastName($lastName);
        $client->setPhoneNumber($phoneNumber);
        $client->setAge($age);
        $client->setRoles(array($roles));

        try{
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();

            return new JsonResponse("Account is created",200);
        } catch (\Exception $ex){
            return new Response("exception".$ex->getMessage());
        }

    }

    /**
     * @Route("client/signin",name="app_loginmobile")
     */

    public function signinAction(Request $request){

        $username = $request->query->get("username");
        $password = $request->query->get("password");

        $em= $this->getDoctrine()->getManager();
        $client= $em->getRepository(Client::class)->findOneBy(['username'=>$username]);// find user by username

        if($client){
            if(password_verify($password,$client->getPassword())) {
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($client);
                return new JsonResponse($formatted);
            }
            else{
                return new Response("password not found");
            }

            }
        else{
            return new Response("user not found");

        }
    }

    /**
     * @Route("client/editClient",name="app_gestion_profile")
     */

    public function editClient(Request $request,UserPasswordEncoderInterface $passwordEncoder){
        $id = $request->get("id");
        $username = $request->query->get("username");
        $password = $request->query->get("password");
        $email = $request->query->get("email");
        $firstName = $request->query->get("firstName");
        $lastName = $request->query->get("lastName");
        $phoneNumber = $request->query->get("phoneNumber");
        $age = $request->query->get("age");
        $address = $request->query->get("address");

        $em =$this->getDoctrine()->getManager();
        $client = $em->getRepository(Client::class)->find($id);

        $client->setUsername($username);
        $client->setEmail($email);
        $client->setPassword($passwordEncoder->encodePassword($client,$password));
        $client->setFirstName($firstName);
        $client->setLastName($lastName);
        $client->setPhoneNumber($phoneNumber);
        $client->setAge($age);

        try{
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();

            return new JsonResponse("success",200);
        } catch (\Exception $ex){
            return new Response("fail".$ex->getMessage());
        }


    }



}
