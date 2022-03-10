<?php

namespace App\Controller;

use App\Entity\Crypto\Node;
use App\Form\NodeType;
use App\Repository\NodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NodeController extends AbstractController
{
    /**
     * @Route("/node", name="node")
     */
    public function index(): Response
    {
        return $this->render('node/index.html.twig', [
            'controller_name' => 'NodeController',
        ]);
    }

    /**
     * @Route("/admin/searchNode", name="search-node")
     */
    public  function searchNode(Request $request,NodeRepository $nodeRepository):Response
    {
        $search = $request->get('search-term');
        $nodes = $nodeRepository->searchByName($search);
        return $this->render("node/viewNodes.html.twig",[
            "nodes" => $nodes
        ]);

    }

    /**
     * @Route("/admin/createNode", name="create-node")
     * @param Request $request
     * @return Response
     */
    public function createNode(Request $request): Response
    {
        $node = new Node();
        $form = $this->createForm(NodeType::class,$node);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() )
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($node);
            $em->flush();

            $this->addFlash('success','Node Created ! Good job');
            return $this->redirectToRoute('view-nodes');
        }


        return $this->render('node/createNode.html.twig', [
            'node_creation_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/viewNodes",name="view-nodes")
     * @param NodeRepository $nodeRepository
     * @return Response
     */
    public function viewNodes(NodeRepository $nodeRepository):Response
    {
        $nodes = $nodeRepository->findAll();
        return $this->render("node/viewNodes.html.twig",[
            "nodes" => $nodes
        ]);
    }

    /**
     * @Route("/admin/viewNodeInfo/{nodeId}",name="view-node-info")
     * @param int $nodeId
     * @param NodeRepository $nodeRepository
     * @return Response
     */
    public function viewOneNode(int $nodeId , NodeRepository $nodeRepository):Response
    {
        $node = $nodeRepository->find($nodeId);
        return $this->render("node/viewNodeInfo.html.twig",[
            "node" => $node
        ]);
    }

    /**
     * @Route("/admin/deleteNode/{{nodeId}}",name="delete-node")
     * @param int $nodeId
     * @param NodeRepository $nodeRepository
     * @return Response
     */
    public function deleteNode(int $nodeId, NodeRepository $nodeRepository):Response
    {
        $nodeToDelete = $nodeRepository->find($nodeId);
        $em = $this->getDoctrine()->getManager();
        $em->remove($nodeToDelete);
        $em->flush();
        return $this->redirectToRoute("view-nodes");

    }

    /**
     * @Route("/admin/updateNode/{{nodeId}}", name="update-node")
     * @param Request $request
     * @param int $nodeId
     * @param NodeRepository $nodeRepository
     * @return Response
     */
    public function updateNode(Request $request,int $nodeId, NodeRepository $nodeRepository):Response
    {
        $nodeToUpdate = $nodeRepository->find($nodeId);
        if (!$nodeToUpdate) {
            throw $this->createNotFoundException(
                'No Node found ,Go Back & Try Again ! '
            );
        }


        $form = $this->createForm(NodeType::class,$nodeToUpdate);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("view-node-info",["nodeId" => $nodeId]);
        }

        return $this->render("node/updateNode.html.twig",[
            "node_update_form" => $form->createView()
        ]);
    }

}
