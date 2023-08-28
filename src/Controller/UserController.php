<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends AbstractController
{
    #[Security("is_granted('ROLE_USER') and user == choseuser")]
    #[Route('/usere/edit/{id}', name: 'user.edit', methods:['GET','POST'])]
    public function edit( User $choseuser,Request $request,EntityManagerInterface $manager, UserPasswordHasherInterface $hasher  ): Response
    {
        
        $form = $this->createForm(UserType::class, $choseuser);
           $form->handleRequest($request);
           if($form->isSubmitted() && $form->isValid()){
       if($hasher->isPasswordValid($choseuser,$form->getData()->getPlainpassword()))
{

            $user = $form->getData();
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                'les information de votre compte on bien ete modifieres'
            );
            return $this->redirectToRoute('recipes.index');

}else{
    $this->addFlash(
        'warning',
        'le mot de passe renseigne est incorrect'
    );
}

           }

         return $this->render('pages/user/edit.html.twig',[
        'form' => $form->createView()
    ]
    ); 
    }

    #[Security("is_granted('ROLE_USER') and user == choseuser")]
    #[Route('/usermot/edit/{id}', name: 'editpassword.edit', methods:['GET','POST'])]
    public function editPassword(User $choseuser, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $plainPassword = $formData['plainpassword'];
            $newPassword = $formData['newPassword'];
    
            if ($hasher->isPasswordValid($choseuser, $plainPassword)) {
                $hashedNewPassword = $hasher->hashPassword($choseuser, $newPassword);
    
                $choseuser->setPassword($hashedNewPassword);
    
                $manager->persist($choseuser);
                $manager->flush();
    
                $this->addFlash(
                    'success',
                    'Le mot de passe a bien été modifié.'
                );
    
                return $this->redirectToRoute('recipes.index');
            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect.'
                );
            }
        }
    
        return $this->render('pages/user/editpassword.html.twig', [
            'form' => $form->createView()
        ]);
    }
    

}



