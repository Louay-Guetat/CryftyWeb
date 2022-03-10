<?php

namespace App\Controller;

use App\Entity\Users\Client;
use App\Entity\Users\Moderator;
use App\Form\RegistrationClientType;
use App\Form\RegistrationModeratorType;
use App\Form\UpdateModeratorType;
use App\Repository\ModeratorRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\ClientRepository;
class ModeratorController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/registermoderator", name="registermoderator")
     */
    public function register(Request $request)
    {
        $user = new Moderator();

        $form = $this->createForm(RegistrationModeratorType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_MODERATOR']);
            $user->setIsActive(false);

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('moderator/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/updatemoderator/{id}", name="updatemoderator")
     */
    public function updateUpdate(Request $request,ModeratorRepository $repository,$id)
    {
        $moderator=$repository->find($id);
        $form = $this->createForm(UpdateModeratorType::class, $moderator);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $moderator->setPassword($this->passwordEncoder->encodePassword($moderator, $moderator->getPassword()));


            // Save
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute('Clientlist');
        }

        return $this->render('moderator/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/moderator/delete/{id}", name="delete_moderator")
     */
    public function deletemoderator($id,ModeratorRepository $repository) {

        $user=$repository->find($id);;
        $em=$this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('moderatorlist');
    }


    /**
     * @return Response
     * @Route ("/Moderatorlist",name="moderatorlist")
     */
    public function Listclient(ModeratorRepository $repository,Request $request,PaginatorInterface $paginator){
        $donnees=$repository->findAll();
        $moderator = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );
        return $this->render('moderator/moderatorlist.html.twig',['moderator'=>$moderator ]);
    }

    /**
     * @Route("/Moderator/{id}", name="show_moderator")
     */
    public function ShowModerator(int $id,ModeratorRepository $repository)
    {
        $Moderator =$repository->find($id);

        return $this->render("moderator/showmoderator.html.twig", [
            "m" => $Moderator,
        ]);
    }
}
