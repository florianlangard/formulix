<?php

namespace App\Controller\Back;

use App\Entity\Result;
use App\Form\ResultType;
use App\Repository\ResultRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/result")
 */
class ResultController extends AbstractController
{
    /**
     * @Route("/", name="back_result_index", methods={"GET"})
     */
    public function index(ResultRepository $resultRepository): Response
    {
        return $this->render('back/result/index.html.twig', [
            'results' => $resultRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="back_result_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $result = new Result();
        $form = $this->createForm(ResultType::class, $result);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($result);
            $entityManager->flush();

            return $this->redirectToRoute('back_result_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/result/new.html.twig', [
            'result' => $result,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_result_show", methods={"GET"})
     */
    public function show(Result $result): Response
    {
        return $this->render('back/result/show.html.twig', [
            'result' => $result,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="back_result_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Result $result, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ResultType::class, $result);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('back_result_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/result/edit.html.twig', [
            'result' => $result,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_result_delete", methods={"POST"})
     */
    public function delete(Request $request, Result $result, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$result->getId(), $request->request->get('_token'))) {
            $entityManager->remove($result);
            $entityManager->flush();
        }

        return $this->redirectToRoute('back_result_index', [], Response::HTTP_SEE_OTHER);
    }
}
