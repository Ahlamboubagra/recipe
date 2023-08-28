<?php
namespace App\Controller;
use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class MyIngredientController extends AbstractController
{
    /**
     *THIS FUNCTION DISPLAY ALL INGREDIENT GET (affichage)
     *
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */

    #[Route('/ingredient', name: 'ingredient.index', methods:['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator   ,Request $request): Response
    {
        // PaginatorInterface $paginator

        //REDING
        $ingredients = $paginator->paginate(
            $repository->findBy(['user'=>$this->getUser()]),
        
            $request->query->getInt('page', 1), // Current page number
            10 // Number of items per page
        );
        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' =>  $ingredients
        ]);
    }

//created
#[IsGranted('ROLE_USER')] 
#[Route("/ingredient/nouveau", name: "ingredient.new", methods: ['GET', 'POST'])]

public function new(Request $request ,EntityManagerInterface $manager  ): Response {
      $ingredient= new Ingredient();
    $form = $this->createForm(IngredientType::class, $ingredient);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
       
              $ingredient = $form->getData();
              $ingredient->setUser($this->getUser());
          $manager->persist($ingredient);
          $manager->flush();
          $this->addFlash(
            'success',
            'votre ingredient a ete cree et success!'
        );
        return $this->redirectToRoute('ingredient.index');

    }
    return $this->render('pages/ingredient/new.html.twig',[ 
          'form' => $form->createView()
        ]
    
   ); 
}
//edit updat modificacion
#[Security("is_granted('ROLE_USER') and user == ingredient.getUser()")]
#[Route("/ingredient/edit/{id}", name:"ingredient.edit", methods: ['GET', 'POST'])]
public function edit(Ingredient $ingredient , Request $request ,EntityManagerInterface $manager  ):Response {
    $form = $this->createForm(IngredientType::class, $ingredient);
    
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
       
              $ingredient = $form->getData();
          $manager->persist($ingredient);
          $manager->flush();
          $this->addFlash(
            'success',
            'votre ingredient a ete modifie et success!'
        );
        return $this->redirectToRoute('ingredient.index');
}
    return $this->render('pages/ingredient/edit.html.twig' ,[
        'form' => $form->createView()
    ]
    );


}

// delect 
#[Route("/ingredient/delet/{id}", name:"ingredient.delet", methods: [  'Get','POST'])]
public function delete(Ingredient $ingredient , Request $request ,EntityManagerInterface $manager ):Response{

   if(!$ingredient){
    $this->addFlash(
        'success',
        'L\ ingredient en question n \a pas ete trouve!'

    );
    return $this->redirectToRoute('ingredient.index');
   }
$manager->remove($ingredient);
$manager->flush();

$this->addFlash(
    'success',
    'votre ingredient a ete supprimer et success!'

);
return $this->redirectToRoute('ingredient.index');


}




}