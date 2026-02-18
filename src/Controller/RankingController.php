<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Ranking;
use App\Entity\RankingAnime;
use App\Form\RankingType;
use App\Repository\RankingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ranking')]
final class RankingController extends AbstractController
{
    #[Route(name: 'app_ranking_index', methods: ['GET'])]
    public function index(RankingRepository $rankingRepository): Response
    {
        return $this->render('ranking/index.html.twig', [
            'rankings' => $rankingRepository->findAll(),
        ]);
    }

    #[Route('/category/{id}/ranking/new', name: 'app_ranking_new', methods: ['GET', 'POST'])]
    public function new(Category $category, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $existe = $entityManager->getRepository(Ranking::class)->findOneBy(['user' => $user, 'category' => $category]);

        if ($existe) {
            $this->addFlash('error', 'Ya tienes un ranking para esta categoría');
            return $this->redirectToRoute('app_ranking_edit', ['id' => $existe->getId()]);
        }

        $ranking = new Ranking();
        $ranking->setUser($user);
        $ranking->setCategory($category);

        foreach ($category->getAnimes() as $anime) {
            $rankingAnime = new RankingAnime();
            $rankingAnime->setAnime($anime);
            $rankingAnime->setRanking($ranking);
            $ranking->addRankingAnime($rankingAnime);
        }

        $form = $this->createForm(RankingType::class, $ranking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $positions = [];
            foreach ($ranking->getRankingAnimes() as $rankingAnime) {
                $pos = $rankingAnime->getPosition();
                if ($pos === null){
                    $this->addFlash('error', 'Todas las posiciones deben estar cubiertas.');
                    return $this->render('ranking/new.html.twig', [
                        'form' => $form->createView(),
                        'category' => $category,]);
                }
                $pos = (int)$pos;

                if (in_array($pos, $positions, true)) {
                    $this->addFlash('error', 'La posición no puede repetirse.');
                    return $this->render('ranking/new.html.twig', [
                        'form' => $form->createView(),
                        'category' => $category,]);
                }
                $positions[] = $pos;
            }
            $entityManager->persist($ranking);
            $entityManager->flush();
            $this->addFlash('success', 'Ranking creado correctamente');
            return $this->redirectToRoute('app_ranking_show', ['id' => $ranking->getId()]);

        }

        return $this->render('ranking/new.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }

    #[Route('/{id}', name: 'app_ranking_show', methods: ['GET'])]
    public function show(Ranking $ranking): Response
    {
        return $this->render('ranking/show.html.twig', [
            'ranking' => $ranking,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ranking_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ranking $ranking, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RankingType::class, $ranking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ranking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ranking/edit.html.twig', [
            'ranking' => $ranking,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ranking_delete', methods: ['POST'])]
    public function delete(Request $request, Ranking $ranking, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ranking->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ranking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ranking_index', [], Response::HTTP_SEE_OTHER);
    }
}
