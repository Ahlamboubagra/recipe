<?php

namespace App\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class SecurityController extends AbstractController
{
    #[Route('/connexion', name: 'security.login' ,methods: ['GET','POST'])]
    public function login(AuthenticationUtils $authenticationUtils ): Response
    {
       
        return $this->render('pages/security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }





    #[Route('/deconnexion',  'security.logout') ]
    public function logout(){
        // nothing to do

    }
    #[Route('/inscription',  'security.registration',methods:['Get','Post']) ]
    public function registration( Request $request,EntityManagerInterface $manager) : Response{
        $user = new User();
        $user->setRoles(['ROLE_ USER']);
        $form = $this->createForm(RegistrationType::class, $user);
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $manager->persist($user);
            $manager->flush();
              $this->addFlash(
                'success',
                'Votre compte a bien été créé!'
            );
            return $this->redirectToRoute('security.login');
        }
        
        return $this->render('pages/security/registration.html.twig', [
            'form' => $form->createView()
        ]);
 
    }
}
