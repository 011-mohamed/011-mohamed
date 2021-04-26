<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gerer le formualire de connexion
     * 
     * @Route("/login", name="account_login")
     *  
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hashError' => $error !== null ,
            'username' => $username

        ]);
    }

    /**
     * Permet de se déconnecter 
     * @Route("/logout", name="account_logout")
     * 
     * @return void
     */
    public function logout(){

    }

    /**
     * Permet d'afficher le formulaire d'inscription 
     * 
     * @Route("/registre", name="account_register")
     * 
     */
    public function register(Request $request , UserPasswordEncoderInterface $encoder){
        $user = new User();
        $form = $this->createForm(RegistrationType::class,$user);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);
            
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte a bien été créé ! Vous pouvez maintenant vous connecter !"
            );
            return $this->redirectToRoute('account_login');
            
        }
        return $this->render('account/registration.html.twig',[
            'form' => $form->createView()

        ]);
    }

    /**
     * Permet d'afficher et de traiter le formulaire de modification de profile
     * 
     * @Route("/account/profile", name="account_profile")
     * @return Response 
     */
    public function profile(Request $request){

        $user = $this->getUser();
        $form = $this->createform(AccountType::class, $user);
        $manager = $this->getDoctrine()->getManager();
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                'Les données du profile ont été enregistrée avec succés !'
            );
        }
        return $this->render('account/profile.html.twig',[
            'form' => $form->createView()
        ]);
    }
    /**
     * Permet de modifier le mot de passe 
     * @Route("/account/password-update", name="account_password")
     *
     * @return Response
     */
    public function updatePassword( Request $request,UserPasswordEncoderInterface $encoder ){
        
        $passwordUpdate = new PasswordUpdate();
        $user = $this->getUser();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $manager = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        
        
        if ($form->isSubmitted() && $form->isValid()) { 
            //1. verfier que le oldPassword du formualire soit le meme que le password de l'user
            if (!password_verify($passwordUpdate->getOldPassword(),$user->getHash())) {
                $form->get('oldPassword')->addError(new FormError
                ("Le mot de passe que vous avez tapé n'est pas votre mot de passe acteul ! "));
                
            }else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                'success',
                'Votre mot de passe a bien été modifié !'
                );

                return $this->redirectToRoute('homepage');
            }
        }
         

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        return $this->render('account/password.html.twig',[
            'form' => $form->createView()
        ]);
    }
    /**
     * Permet d'afficher le profile de utilisateur connecté
     * 
     * @Route("/account" , name="account_index")
     * @return Response
     */
    public function myAccount(){
        return $this->render('user/index.html.twig',[
            'user' => $this->getUser()
        ]) ;
    }
}
