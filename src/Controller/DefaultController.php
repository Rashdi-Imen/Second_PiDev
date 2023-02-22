<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function homePage(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    #[Route('/about', name: 'about')]
    public function aboutPage(): Response
    {
        return $this->render('default/about.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    #[Route('/sevices', name: 'services')]
    public function servicesPage(): Response
    {
        return $this->render('default/services.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    #[Route('/offres', name: 'offers')]
    public function offerPage(): Response
    {
        return $this->render('default/offer.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    #[Route('/team', name: 'team')]
    public function teamPage(): Response
    {
        return $this->render('default/team.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contactPage(): Response
    {
        return $this->render('default/contact.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

}
