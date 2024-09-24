<?php

namespace App\Controller;

use App\Entity\Reading;
use App\Form\ReadingType;
use App\Repository\ReadingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reading')]
class ReadingController extends AbstractController
{
    #[Route('/', name: 'app_reading_index', methods: ['GET'])]
    public function index(ReadingRepository $readingRepository): Response
    {
        return $this->render('reading/index.html.twig', [
            'readings' => $readingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reading_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reading = new Reading();
        $form = $this->createForm(ReadingType::class, $reading);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reading);
            $entityManager->flush();

            return $this->redirectToRoute('app_reading_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reading/new.html.twig', [
            'reading' => $reading,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reading_show', methods: ['GET'])]
    public function show(Reading $reading): Response
    {
        return $this->render('reading/show.html.twig', [
            'reading' => $reading,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reading_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reading $reading, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReadingType::class, $reading);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reading_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reading/edit.html.twig', [
            'reading' => $reading,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reading_delete', methods: ['POST'])]
    public function delete(Request $request, Reading $reading, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reading->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reading);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reading_index', [], Response::HTTP_SEE_OTHER);
    }
}
