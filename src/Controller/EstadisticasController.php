<?php

namespace App\Controller;

use App\Repository\AnimeRepository;
use App\Repository\CategoryRepository;
use App\Repository\RankingAnimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EstadisticasController extends AbstractController
{
    #[Route('/estadisticas', name: 'app_estadisticas')]
    public function index(
        AnimeRepository $animeRepository,
        CategoryRepository $categoryRepository,
        RankingAnimeRepository $rankingAnimeRepository,
    ): Response
    {
        $topRatedAnime = $animeRepository->getTopRatedAnimes();
        $mostRatedAnime = $animeRepository->getMostRatedAnimes();

        return $this->render('estadisticas/index.html.twig', [
            'topRatedAnimes' => $topRatedAnime,
            'mostRatedAnimes' => $mostRatedAnime,
        ]);
    }
}
