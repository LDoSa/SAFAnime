<?php

namespace App\Controller;

use App\Entity\Anime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(HttpClientInterface $httpClient): Response
    {
        $response = $httpClient->request(
            'GET',
            'https://api.jikan.moe/v4/anime'
        );

        $content = $response->toArray();

        foreach ($content['data'] as $elemento) {

            $anime = new Anime();

            $anime->setAniId($elemento['mal_id']);
            $anime->setTitulo($elemento['title']);
            $anime->setImagen($elemento['images']['jpg']['image_url']);
            $anime->setEpisodios($elemento['episodes']);
        }

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'anime' => $content['data'],
        ]);
    }
}
