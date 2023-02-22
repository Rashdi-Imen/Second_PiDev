<?php

namespace App\Controller;

use App\Entity\Citoyen;
use App\Form\ChangePasswordType;
use App\Form\CitoyenType;
use App\Form\ProfileType;
use App\Repository\CitoyenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
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


    #[Route('/profile/citoyen', name: 'app_citoyen_profile')]
    public function profile(Request $request): Response
    {

        $citoyen = $this->getUser();

        if ($citoyen instanceof Citoyen) {
            $form = $this->createForm(ProfileType::class, $citoyen);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
               //EntityManger est un service Doctrine qui nous permet de manipuler des entités
                $entityManager = $this->getDoctrine()->getManager();
                //flush : mise à jour dans la BD
                $entityManager->flush();

                $this->addFlash('success', 'Profil mis à jour avec succès.');
                
                // baaed matbadel avec succes bsh yerjaali lel page
                return $this->redirectToRoute('app_citoyen_profile');
            }

            return $this->render('citoyen/profile.html.twig', [
                'form' => $form->createView(),
                
            ]);
        }

        throw new \LogicException('Erreur : l\'utilisateur courant n\'est pas un citoyen.');
    
        // return $this->render('profile/med_profile.html.twig');
    }

    #[Route('/citoyen/change-password', name: 'app_citoyen_change-password')]
    public function changePassword(Request $request)
    {
        $form = $this->createForm(ChangePasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $password = $form->get('password')->getData();
            $ConfirmPassword = $form->get('confirm_password')->getData();
            $encoder = $this->userPasswordEncoder->encodePassword($user, $password, $ConfirmPassword);
            $user->setPassword($encoder);
            $user->setConfirmPassword($encoder);

            $entityManager = $this->getDoctrine()->getManager();// appel au ORM
            $entityManager->persist($user);//persist=sauvgarde
            $entityManager->flush();//mise à jour et enrigstrement de modification

            $this->addFlash('success', 'Votre mot de passe a été changé avec succès.');

            return $this->redirectToRoute('app_citoyen_profile');
        }

        return $this->render('citoyen/change_password_citoyen.html.twig', [
            'form' => $form->createView(),
        ]);
    }
 
    

   




}

