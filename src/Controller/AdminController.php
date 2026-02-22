<?php

namespace App\Controller;

use App\Entity\Anime;
use App\Entity\User;
use App\Repository\OpinionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/user/{id}/opinions', name: 'app_admin_user_opinions')]
    #[IsGranted('ROLE_ADMIN')]
    public function userOpinions(User $user, OpinionRepository $opinionRepository): Response
    {
        $opinions = $opinionRepository->findBy(['user' => $user]);
        return $this->render('admin/user_opinions.html.twig', [
            'user' => $user,
            'opinions' => $opinions,
        ]);
    }
}
