<?php

namespace App\Controller;

use App\Entity\Testign;
use App\Form\TestignType;
use App\Repository\TestignRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/testign')]
class TestignController extends AbstractController
{
    #[Route('/', name: 'app_testign_index', methods: ['GET'])]
    public function index(TestignRepository $testignRepository): Response
    {
        return $this->render('testign/index.html.twig', [
            'testigns' => $testignRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_testign_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $testign = new Testign();
        $form = $this->createForm(TestignType::class, $testign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($testign);
            $entityManager->flush();

            return $this->redirectToRoute('app_testign_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('testign/new.html.twig', [
            'testign' => $testign,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_testign_show', methods: ['GET'])]
    public function show(Testign $testign): Response
    {
        return $this->render('testign/show.html.twig', [
            'testign' => $testign,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_testign_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Testign $testign, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TestignType::class, $testign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_testign_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('testign/edit.html.twig', [
            'testign' => $testign,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_testign_delete', methods: ['POST'])]
    public function delete(Request $request, Testign $testign, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$testign->getId(), $request->request->get('_token'))) {
            $entityManager->remove($testign);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_testign_index', [], Response::HTTP_SEE_OTHER);
    }
}
