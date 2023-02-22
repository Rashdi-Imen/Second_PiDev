<?php

namespace App\Controller;

use App\Entity\CategorieOffres;
use App\Form\CategorieOffres1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorie/offres')]
class CategorieOffresController extends AbstractController
{
    #[Route('/', name: 'app_categorie_offres_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categorieOffres = $entityManager
            ->getRepository(CategorieOffres::class)
            ->findAll();

        return $this->render('categorie_offres/index.html.twig', [
            'categorie_offres' => $categorieOffres,
        ]);
    }

    #[Route('/new', name: 'app_categorie_offres_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorieOffre = new CategorieOffres();
        $form = $this->createForm(CategorieOffres1Type::class, $categorieOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorieOffre);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_offres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_offres/new.html.twig', [
            'categorie_offre' => $categorieOffre,
            'form' => $form,
        ]);
    }

    #[Route('/{idCategorie}', name: 'app_categorie_offres_show', methods: ['GET'])]
    public function show(CategorieOffres $categorieOffre): Response
    {
        return $this->render('categorie_offres/show.html.twig', [
            'categorie_offre' => $categorieOffre,
        ]);
    }

    #[Route('/{idCategorie}/edit', name: 'app_categorie_offres_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieOffres $categorieOffre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieOffres1Type::class, $categorieOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_offres_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_offres/edit.html.twig', [
            'categorie_offre' => $categorieOffre,
            'form' => $form,
        ]);
    }

    #[Route('/{idCategorie}', name: 'app_categorie_offres_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieOffres $categorieOffre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieOffre->getIdCategorie(), $request->request->get('_token'))) {
            $entityManager->remove($categorieOffre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorie_offres_index', [], Response::HTTP_SEE_OTHER);
    }
}
