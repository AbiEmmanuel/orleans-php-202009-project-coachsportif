<?php

namespace App\Controller;

use App\Entity\CGV;
use App\Form\CGVType;
use App\Repository\CGVRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/cgv")
 */
class AdminCgvController extends AbstractController
{
    /**
     * @Route("/", name="cgv_admin", methods={"GET"})
     * @param CGVRepository $cgvRepository
     * @return Response
     */
    public function index(CGVRepository $cgvRepository): Response
    {
        return $this->render('admin/cgv/index.html.twig', [
            'cgv' => $cgvRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cgv_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $cgv = new CGV();
        $form = $this->createForm(CGVType::class, $cgv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cgv);
            $entityManager->flush();

            return $this->redirectToRoute('cgv_admin');
        }

        return $this->render('admin/cgv/new.html.twig', [
            'cgv' => $cgv,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cgv", methods={"GET"})
     * @param CGV $cgv
     * @return Response
     */
    public function show(CGV $cgv): Response
    {
        return $this->render('admin/cgv/show.html.twig', [
            'cgv' => $cgv,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cgv", methods={"GET","POST"})
     * @param Request $request
     * @param CGV $cgv
     * @return Response
     */
    public function edit(Request $request, CGV $cgv): Response
    {
        $form = $this->createForm(CGVType::class, $cgv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Les conditions générales de vente ont bien été modifié');

            return $this->redirectToRoute('cgv_admin');
        }

        return $this->render('admin/cgv/edit.html.twig', [
            'cgv' => $cgv,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cgv_delete", methods={"DELETE"})
     * @param Request $request
     * @param CGV $cgv
     * @return Response
     */
    public function delete(Request $request, CGV $cgv): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cgv->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cgv);
            $entityManager->flush();
            $this->addFlash('danger', 'Les conditions générales de vente ont bien été supprimé');
        }

        return $this->redirectToRoute('cgv_admin');
    }
}
