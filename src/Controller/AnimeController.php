<?php

namespace App\Controller;

use App\Entity\Anime;
use App\Form\AnimeType;
use App\Form\OpinionType;
use App\Repository\AnimeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/anime')]
final class AnimeController extends AbstractController
{
    #[Route(name: 'app_anime_index', methods: ['GET'])]
    public function index(AnimeRepository $animeRepository): Response
    {
        return $this->render('anime/index.html.twig', [
            'animes' => $animeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_anime_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $anime = new Anime();
        $form = $this->createForm(AnimeType::class, $anime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($anime);
            $entityManager->flush();

            return $this->redirectToRoute('app_anime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('anime/new.html.twig', [
            'anime' => $anime,
            'form' => $form,
        ]);
    }

    #[Route('/ver/{id}', name: 'app_anime_show', methods: ['GET'])]
    public function show(Anime $anime, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $opinion = new \App\Entity\Opinion();
        $opinion->setAnime($anime);
        if ($user){
            $opinion->setUser($user);
        }

        $form_opinion = $this->createForm(\App\Form\OpinionType::class, $opinion);

        return $this->render('anime/show.html.twig', [
            'anime' => $anime,
            'form_opinion' => $form_opinion->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_anime_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Anime $anime, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnimeType::class, $anime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_anime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('anime/edit.html.twig', [
            'anime' => $anime,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_anime_delete', methods: ['POST'])]
    public function delete(Request $request, Anime $anime, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$anime->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($anime);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_anime_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/importar/animes', name: 'importar_anime')]
    public function import(HttpClientInterface $httpClient, EntityManagerInterface $entityManager): Response{

        $todosAnimes = [];

        for ($page = 1; $page <= 10; $page++) {
            $response = $httpClient->request(
                'GET',
                'https://api.jikan.moe/v4/anime?page=' . $page
            );

            $content = $response->toArray();

            $todosAnimes = array_merge($todosAnimes, $content['data']);

            if (!$content['pagination']['has_next_page']) {
                break;
            }

            sleep(1);
        }

        foreach ($todosAnimes as $data) {

            $existingAnime = $entityManager->getRepository(Anime::class)->findOneBy(['ani_id' => $data['mal_id']]);

            if ($existingAnime) {
                continue;
            }

            $item = new Anime();
            $item->setAniId($data['mal_id']);
            $item->setTitulo($data['title']);
            $item->setImagen($data['images']['jpg']['image_url']);
            $item->setEpisodios($data['episodes']);
            $entityManager->persist($item);
        }

        $entityManager->flush();

        return new Response('Todo a la base de datos');
    }

}
