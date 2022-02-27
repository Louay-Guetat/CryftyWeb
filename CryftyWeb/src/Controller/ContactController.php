<?php

namespace App\Controller;

use App\Entity\Users\SupportTicket;
use App\Entity\Users\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactFormType;
use App\Repository\SupportTicketRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



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


    /**
     * @Route ("/contact/Add",name="Contact_add")
     */
    public function new(Request $request)
    {
        $SupportTicket=new SupportTicket();
        $form = $this->createForm(ContactFormType::class,$SupportTicket);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($SupportTicket);
            $em->flush();
            $this->addFlash('success','Bien cree avec succes');
            return $this->redirectToRoute('registration');
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
     * @Route ("/contactlist/",name="contactlist")
     */
    public function Listclient(SupportTicketRepository $repository){
        $SupportTicket=$repository->findAll();
        return $this->render('contact/contactlist.html.twig',['form'=>$SupportTicket ]);
    }

    /**
     * @Route("/Contact/{id}", name="show_contact")
     */
    public function ShowContact(int $id,SupportTicketRepository $repository)
    {
        $Ticket =$repository->find($id);

        return $this->render("contact/showcontact.html.twig", [
            "T" => $Ticket,
        ]);
    }

}
