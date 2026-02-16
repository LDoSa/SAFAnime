<?php

namespace App\Controller;

use App\Entity\Anime;
use App\Entity\Opinion;
use App\Entity\User;
use App\Form\OpinionType;
use App\Repository\OpinionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/opinion')]
final class OpinionController extends AbstractController
{
    #[Route(name: 'app_opinion_index', methods: ['GET'])]
    public function index(OpinionRepository $opinionRepository): Response
    {
        return $this->render('opinion/index.html.twig', [
            'opinions' => $opinionRepository->findAll(),
        ]);
    }

    #[Route('/new/{animeId}', name: 'app_opinion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $animeId): Response
    {
        $user = $this->getUser();
        if (!$user){
            return $this->redirectToRoute('app_login');
        }

        $anime = $entityManager->getRepository(Anime::class)->find($animeId);
        if (!$anime){
            throw $this->createNotFoundException('Anime not found');
        }

        $existe = $entityManager->getRepository(Opinion::class)
            ->findOneBy(['user' => $user, 'anime' => $anime]);

        if ($existe){
            $this->addFlash('error', 'Opinion already exist');
            return $this->redirectToRoute('app_anime_show', ['id' => $animeId]);
        }


        $opinion = new Opinion();
        $opinion->setAnime($anime)->setUser($user);

        $form = $this->createForm(OpinionType::class, $opinion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($opinion);
            $entityManager->flush();

            $this->addFlash('success', 'Opinion added successfully');
            return $this->redirectToRoute('app_anime_show', ['id' => $animeId]);
        }

        return $this->render('opinion/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_opinion_show', methods: ['GET'])]
    public function show(Opinion $opinion): Response
    {
        return $this->render('opinion/show.html.twig', [
            'opinion' => $opinion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_opinion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Opinion $opinion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OpinionType::class, $opinion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_opinion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('opinion/edit.html.twig', [
            'opinion' => $opinion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_opinion_delete', methods: ['POST'])]
    public function delete(Request $request, Opinion $opinion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$opinion->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($opinion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_opinion_index', [], Response::HTTP_SEE_OTHER);
    }
}
