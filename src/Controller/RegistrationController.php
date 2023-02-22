<?php

namespace App\Controller;

use App\Entity\Citoyen;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LogInFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request,UserRepository $userRepository,UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new Citoyen();
       
       
        $form = $this->createForm(RegistrationFormType::class, $user);
        
        $form->handleRequest($request);//recuperrer la req

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getPassword()&&$user->getConfirmPassword()) {

               
            $user->setPassword($userPasswordHasher->hashPassword($user,$form->get('password')->getData() ));
            $user->setConfirmPassword($userPasswordHasher->hashPassword($user,$form->get('confirm_password')->getData()));
            $user->eraseCredentials();//agree terms
            }
            $roles[]='ROLE_CITOYEN';
            $user->setRoles($roles);
            $userRepository->save($user, true);
            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_login', [$userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
        )], Response::HTTP_SEE_OTHER);
            }
           
        
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);

        // return $this->redirectToRoute('app_login');
    }
}
