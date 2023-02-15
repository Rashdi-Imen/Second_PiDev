<?php

namespace App\Controller;

use App\Entity\Citoyen;
use App\Form\CitoyenType;
use App\Repository\CitoyenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
#[Route('/citoyen')]
class CitoyenController extends AbstractController
{
    private $userPasswordEncoder;
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }
  

    #[Route('/new', name: 'app_citoyen_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CitoyenRepository $citoyenRepository): Response
    {
        $citoyen = new Citoyen();
        $form = $this->createForm(CitoyenType::class, $citoyen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($citoyen->getPassword()&&$citoyen->getConfirmPassword()) {
                $citoyen->setPassword(
                    $this->userPasswordEncoder->encodePassword($citoyen, $citoyen->getPassword())
                );
                $citoyen->setConfirmPassword(
                    $this->userPasswordEncoder->encodePassword($citoyen, $citoyen->getConfirmPassword())
                );
                $citoyen->eraseCredentials();
            }
            $roles[]='ROLE_CITOYEN';
            $citoyen->setRoles($roles);
            $citoyenRepository->save($citoyen, true);

            return $this->redirectToRoute('app_citoyen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('citoyen/new.html.twig', [
            'citoyen' => $citoyen,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_citoyen_show', methods: ['GET'])]
    public function show(Citoyen $citoyen): Response
    {
        return $this->render('citoyen/show.html.twig', [
            'citoyen' => $citoyen,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_citoyen_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Citoyen $citoyen, CitoyenRepository $citoyenRepository): Response
    {
        $form = $this->createForm(CitoyenType::class, $citoyen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $citoyenRepository->save($citoyen, true);

            return $this->redirectToRoute('app_citoyen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('citoyen/edit.html.twig', [
            'citoyen' => $citoyen,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_citoyen_delete', methods: ['POST'])]
    public function delete(Request $request, Citoyen $citoyen, CitoyenRepository $citoyenRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$citoyen->getId(), $request->request->get('_token'))) {
            $citoyenRepository->remove($citoyen, true);
        }

        return $this->redirectToRoute('app_citoyen_index', [], Response::HTTP_SEE_OTHER);
    }
}
