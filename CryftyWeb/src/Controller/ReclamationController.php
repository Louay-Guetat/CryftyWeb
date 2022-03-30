<?php

namespace App\Controller;

use App\Entity\Users\SupportTicket;
use App\Repository\SupportTicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;


class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation", name="reclamation")
     */
    public function index(): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }

    /******************Ajouter Reclamation*****************************************/
    /**
     * @Route("/addReclamation", name="add_reclamation")
     * @Method("POST")
     */

    public function ajouterReclamationAction(Request $request)
    {
        $SupportTicket = new SupportTicket();
        $message = $request->query->get("message");
        $subject = $request->query->get("subject");
        $name = $request->query->get("name");
        $email = $request->query->get("email");
        $em = $this->getDoctrine()->getManager();

        $SupportTicket->setSubject($subject);
        $SupportTicket->setMessage($message);
        $SupportTicket->setName($name);
        $SupportTicket->setEmail($email);
        $SupportTicket->setEtat("En attente");

        $em->persist($SupportTicket);
        $em->flush();
        return $this->json($SupportTicket,200,[],['groups'=>['supportTicket:read']]);

    }

    /******************Supprimer Reclamation*****************************************/

    /**
     * @Route("/deleteReclamation", name="delete_reclamation")
     * @Method("DELETE")
     */

    public function deleteReclamationAction(Request $request) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $SupportTicket = $em->getRepository(SupportTicket::class)->find($id);
        if($SupportTicket!=null ) {
            $em->remove($SupportTicket);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Reclamation a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id reclamation invalide.");


    }

    /******************Modifier Reclamation*****************************************/
    /**
     * @Route("/updateReclamation", name="update_reclamation")
     * @Method("PUT")
     */
    public function modifierReclamationAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $SupportTicket = $this->getDoctrine()->getManager()
            ->getRepository(SupportTicket::class)
            ->find($request->get("id"));

        $SupportTicket()->setSubject($request->get("subject"));
        $SupportTicket->setMessage($request->get("message"));
        $SupportTicket->setName($request->get("name"));
        $SupportTicket->setEmail($request->get("email"));

        $em->persist($SupportTicket);
        $em->flush();
        return new JsonResponse("Reclamation a ete modifiee avec success.");

    }



    /******************affichage Reclamation*****************************************/

    /**
     * @Route("/displayReclamations", name="display_reclamation")
     */
    public function allRecAction()
    {

        $SupportTicket = $this->getDoctrine()->getManager()->getRepository(SupportTicket::class)->findAll();
        return $this->json($SupportTicket,200,[],['groups'=>['supportTicket:read']]);
    }


    /******************Detail Reclamation*****************************************/

    /**
     * @Route("/detailReclamation", name="detail_reclamation")
     * @Method("GET")
     */

    //Detail Reclamation
    public function detailReclamationAction(Request $request)
    {
        $id = $request->get("id");
        $SupportTicket = $this->getDoctrine()->getManager()->getRepository(SupportTicket::class)->find($id);
        return $this->json($SupportTicket,200,[],['groups'=>['supportTicket:read']]);
    }


}
