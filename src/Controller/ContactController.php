<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use App\service\MailService;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact.index')]
    public function index(Request $request ,EntityManagerInterface $manager, 
    MailService $mailService
    
    ): Response
    {
        $contact=new Contact();
        if($this->getUser()){
            // $contact->setFullName($this->getUser()->getFullName())
            //          ->setEmail($this->getUser()->getEmail());
        }
        $form = $this->createForm(ContactType::class,$contact);
        $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
        $contact= $form->getData();
          $manager->persist($contact);
          $manager->flush();
          //email
          $mailService->sendEmail(
            $contact->getEmail(),
            $contact->getSubject(),
            'Email\contact.html.twig',
                ['contact'=>$contact]
          
         );
  
// pass variblles (name=>value) to the template

    //         ->context([
    //             // 'expiration_date'=>new \DateTime('+7 days'),
    //             // 'username'=>'foo',
    //             'contact' => $contact

    //         ]);
    //   $mailer->send($email);
          $this->addFlash(
            'success',
            'votre a ete cree et success!'
        );
      
        return $this->redirectToRoute('contact.index');
    }
        return $this->render('pages/contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
