<?php

namespace App\Controller;

use App\Entity\RankingAnime;
use App\Form\RankingAnimeType;
use App\Repository\RankingAnimeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ranking/anime')]
final class RankingAnimeController extends AbstractController
{
    #[Route(name: 'app_ranking_anime_index', methods: ['GET'])]
    public function index(RankingAnimeRepository $rankingAnimeRepository): Response
    {
        return $this->render('ranking_anime/index.html.twig', [
            'ranking_animes' => $rankingAnimeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ranking_anime_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rankingAnime = new RankingAnime();
        $form = $this->createForm(RankingAnimeType::class, $rankingAnime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rankingAnime);
            $entityManager->flush();

            return $this->redirectToRoute('app_ranking_anime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ranking_anime/new.html.twig', [
            'ranking_anime' => $rankingAnime,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ranking_anime_show', methods: ['GET'])]
    public function show(RankingAnime $rankingAnime): Response
    {
        return $this->render('ranking_anime/show.html.twig', [
            'ranking_anime' => $rankingAnime,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ranking_anime_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RankingAnime $rankingAnime, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RankingAnimeType::class, $rankingAnime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ranking_anime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ranking_anime/edit.html.twig', [
            'ranking_anime' => $rankingAnime,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ranking_anime_delete', methods: ['POST'])]
    public function delete(Request $request, RankingAnime $rankingAnime, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rankingAnime->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($rankingAnime);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ranking_anime_index', [], Response::HTTP_SEE_OTHER);
    }
}
