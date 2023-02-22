<?php

namespace App\Controller;

use App\Repository\CitoyenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        return $this->render('dashboard.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/list', name: 'app_dash')]
    public function dash(CitoyenRepository $userRepository): Response
    {
        return $this->render('citoyen/dashboardCitoyen.html.twig', [
            'citoyens' => $userRepository->findAll(),
        ]);
    }
}
