<?php

namespace App\Controller;

use App\Entity\Payment\Cart;
use App\Entity\Users\Client;
use App\Entity\Users\User;
use App\Form\RegistrationClientType;
use App\Form\UpdatePasswClType;
use App\Form\UpdateProfilType;
use App\Repository\ClientRepository;
use App\Repository\NftRepository;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use App\Services\Mailer\MailerService;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


use Exception;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
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

    private $mailerService;

    public function __construct(MailerService $mailerService,Security $security,UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->mailerService = $mailerService;
        $this->security = $security;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/registration", name="registration")
     * @throws \Twilio\Exceptions\TwilioException
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function index(Request $request,UserRepository $repository)
    {
        $user = new Client();

        $form = $this->createForm(RegistrationClientType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
            $newFilename = "defaultImage.png";
            // Set their role
            $user->setRoles(['ROLE_USER']);
            $user->setIsActive(false);
            $user->setAvatar($newFilename);
            $user->setCouverture($newFilename);
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $cart=new Cart();
            $cart->setClientId($user);

            $cart->setTotal(0);
            $em->persist($cart);
            $em->flush();
            $account_sid = 'AC9e2a04a58eb7173bf6c77a21ba9f08d6';
            $auth_token = '34f03ffe77268678bb2d42bce7fa72ff';
            $twilio_number = "+16127784838";

            $client = new \Twilio\Rest\Client($account_sid,$auth_token);
            $client->messages->create(
                '+21624032953',
                array(
                    'from' => $twilio_number,
                    'body' => 'Welcome to Cryfty site, You created a user with the username
                    '.$user->getUsername().'!'
                )
            );

            $emailClient = $user->getEmail();
            $this->mailerService->sendClientVerificationEmail(
                $emailClient,array('FirstName' => $user->getFirstName(),
                    'clientId' => $user->getId(),
                    'username' => $user->getUsername()
                )
            );

            $this->addFlash('success','Compte "'.$user->getFirstName().'" Created . Good job ,
             Check your Email and Activate it! ');

            return $this->redirectToRoute('app_login');
        }
        return $this->render('registration/client.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("client/activateClient/{id}",name="activate-client")
     * @param int $id
     * @param ClientRepository $ClientRepository
     * @return RedirectResponse
     */
    public function activateClient(int $id,ClientRepository $ClientRepository): RedirectResponse
    {
        $clientToActivate = $ClientRepository->find($id);
        $clientToActivate->setIsActive(true);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('app_login');
    }
    /**
     * @Route("client/resendEmail/{id}",name="resend-client-email")
     * @param int $id
     * @param ClientRepository $ClientRepository
     * @return RedirectResponse
     */
    public function resendEmail(int $id,ClientRepository $ClientRepository): RedirectResponse
    {
        $client = $ClientRepository->find($id);
        $client = $this->security->getUser();

        $this->mailerService->sendClientVerificationEmail(
            $client->getEmail(),array('FirstName' => $client->get(),
                'clientId' => $client->getId(),
                'username' => $client->getUsername()
            )
        );
        $this->addFlash('success','Verification Email Resent ! Check again');

        return $this->redirectToRoute('app_login');
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
     * @Route("/profile/{id?}",name="client-profil")
     * @return Response
     */
    public function profileInfo(ClientRepository $clientRepository,Request $request,$id,NftRepository $nftRepository):Response{

        /** @var User $user */
        $loggedUser = $this->security->getUser();

        $userToUpdate = $clientRepository->findOneBy(array("username" => $loggedUser->getUsername()));
        $updateForm = $this->createForm(UpdateProfilType::class,$userToUpdate);
        $updateForm->handleRequest($request);
        if($updateForm->isSubmitted() && $updateForm->isValid())
        {
            $imageFile = $updateForm->get('avatar')->getData();


            if ($imageFile) {

                $safeFilename = bin2hex(random_bytes(16));
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_avatar'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $userToUpdate->setAvatar($newFilename);
            }


            $imageFile2 = $updateForm->get('couverture')->getData();


            if ($imageFile2) {

                $safeFilename = bin2hex(random_bytes(16));
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile2->guessExtension();

                try {
                    $imageFile2->move(
                        $this->getParameter('images_couverture'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $userToUpdate->setCouverture($newFilename);
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }
        $nfts = $nftRepository->findBy(['owner'=>$id]);
        return $this->render('registration/clientProfile.html.twig',['form' => $updateForm->createView(),'nfts'=>$nfts,
            'user' => $userToUpdate
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



