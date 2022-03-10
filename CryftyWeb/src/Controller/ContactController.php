<?php

namespace App\Controller;

use App\Entity\Users\SupportTicket;
use App\Entity\Users\Client;
use App\Services\Mailer\MailerService;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactFormType;
use App\Repository\SupportTicketRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Security;


class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(): Response
    {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
    private $security;
    private $mailerService;
    public function __construct(Security $security,MailerService $mailerService){
        $this->security = $security;
        $this->mailerService = $mailerService;

    }




    /**
     * @Route ("/contact/Add",name="Contact_add")
     */
    public function new(Request $request)
    {
        $SupportTicket=new SupportTicket();
        $client = $this->security->getUser();

        $form = $this->createForm(ContactFormType::class,$SupportTicket);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $SupportTicket->setClient($this->getUser());
            $SupportTicket->setEtat("En attente");
            $em=$this->getDoctrine()->getManager();
            $em->persist($SupportTicket);
            $em->flush();

            $emailClient = $client->getEmail();
            $this->mailerService->sendClientReclamationEmail(
                $emailClient,array('Subject' => $SupportTicket->getSubject(),
                    'supportTicketId' => $SupportTicket->getId(),
                    'username' => $client->getUsername()
                )
            );

            $this->addFlash('success','Subject "'.$SupportTicket->getSubject().'" Created . Good job ,
             Check your Email  ');
            return $this->redirectToRoute('Mreclamation',['id'=> $this->getUser()->getId()]);
        }
        return $this->render('contact/Add.html.twig',['form'=>$SupportTicket,'form'=>$form->createView()]);
    }
    /**
     * @Route("/updatecontact/{id}",name="contact_update")
     */
    function Update(SupportTicketRepository $repository,$id,Request $request){
        $SupportTicket=$repository->find($id);
        $form=$this->createForm(ContactFormType::class,$SupportTicket);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return$this->redirectToRoute("contactlist");
        }
        return $this->render('contact/Update.html.twig',['form'=>$form->createView()]);



    }


    /**
     * @Route("/admin/contact/traite/{id}", name="traite_contact")
     */
    public function traitecontact($id,SupportTicketRepository $repository) {

        $SupportTicket=$repository->find($id);
        $SupportTicket->setEtat('Traité');
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('contactlist');
    }
    /**
     * @Route("/admin/contact/abondone/{id}", name="abondone_contact")
     */
    public function abondonecontact($id,SupportTicketRepository $repository) {

        $SupportTicket=$repository->find($id);
        $SupportTicket->setEtat('Abandonné');
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('contactlist');
    }

    /**
     * @Route("/contact/delete/{id}", name="delete_contact")
     */
    public function deletecontact($id,SupportTicketRepository $repository) {

        $SupportTicket=$repository->find($id);;
        $em=$this->getDoctrine()->getManager();
        $em->remove($SupportTicket);
        $em->flush();
        return $this->redirectToRoute('contactlist');
    }

    /**
     * @return Response
     * @Route ("/admin/contactlist/",name="contactlist")
     */
    public function Listclient(SupportTicketRepository $repository,Request $request,PaginatorInterface $paginator){
        $donnees=$repository->findAll();
        $SupportTicket= $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );

        return $this->render('contact/contactlist.html.twig',['form'=>$SupportTicket ]);
    }


    /**
     * @Route("/admin/Contact/{id}", name="show_contact")
     */
    public function ShowContact(int $id,SupportTicketRepository $repository,Request $request)
    {
        $Ticket =$repository->find($id);

        $form=$this->createForm(ContactFormType::class,$Ticket);
        $Ticket->setEtat("En cours de traitement");
        $em=$this->getDoctrine()->getManager();
        $em->flush();

        return $this->render("contact/showcontact.html.twig", [
            "T" => $Ticket,
        ]);
    }

    /**
     * @Route("contact/AfficheMesreclamations/{id}", name="Mreclamation")
     */
    function afficheReclamation($id,SupportTicketRepository $Repository){

        $Ticket = $Repository->findBy(['Client'=>$id]);
        return $this->render('contact/showmycontact.html.twig',['Ticket'=>$Ticket]);
    }



}
