<?php

namespace App\Controller;

use App\Entity\Recipes;
use App\Entity\Mark;
use App\Form\MakeType;
use App\Form\RecipesType;
use App\Repository\MarkRepository;
use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipesController extends AbstractController
{
    
    #[Route('/recipes', name: 'recipes.index',methods:['GET'])]
    #[IsGranted('ROLE_USER')] 
    public function index(RecipesRepository $repository, PaginatorInterface $paginator   ,Request $request): Response
    {
        $recipes = $paginator->paginate(
            $repository->findBy(['user'=>$this->getUser()]),
        
            $request->query->getInt('page', 1), // Current page number
            10 // Number of items per page
        );
        
        return $this->render('pages/recipes/index.html.twig', [
            'recipes' => $recipes
        ]);
    }


    // details for recipes
#[Security("is_granted('ROLE_USER') and recipe.getIsPublic() == true || user == recipe.getUser()")]
#[Route('/recette/{id}' ,'recipe.show',methods:['GET', 'POST'])]
public function show(Recipes $recipe,
EntityManagerInterface $manager,
     Request $request,
     MarkRepository $markRepository
) : Response {
    $mark =new Mark();
    $form = $this->createForm(MakeType::class, $mark);
    
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
$mark->setUser($this->getUser())
    ->setRecipe($recipe);
    $existingMark = $markRepository->findOneBy([
        'user' => $this->getUser(),
        'recipe' => $recipe
    ]);
    if(!$existingMark){
       $manager->persist($mark);

    }else{
        $existingMark->setMark(
            $form->getData()->getMark()
        );
    }
    $manager->flush();
    $this->addFlash(
      'success',
      'votre note a bien ete prise en compte!'
  );
  return $this->redirectToRoute('recipe.show' ,['id'=>$recipe ->getId()]);
    }



    return $this->render('pages/recipes/show.html.twig',[
        'recipes' => $recipe,
        'form' => $form->createView()
    ]);
}


// recipes public methods
#[Route('/recettepublic' ,'recipe.indexpublic',methods:['GET', 'POST'])]
public function indexPublic(
    RecipesRepository $repository,
     PaginatorInterface $paginator ,
     Request $request

): Response {


    $recipe = $paginator->paginate(
        $repository->findPublicRecipe(null),
    
        $request->query->getInt('page', 1), // Current page number
        10 // Number of items per page
    );
    
    return $this->render('pages/recipes/indexrecip.html.twig',[
        'recipes' => $recipe
    ]);

}



//created 

#[Route("/recipes/nouveau", name: "recipes.new", methods: ['GET', 'POST'])]
#[IsGranted('ROLE_USER')] 
public function new(Request $request ,EntityManagerInterface $manager  ): Response {
      $recipes= new Recipes();
    $form = $this->createForm(RecipesType::class,$recipes );
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
       
        $recipes = $form->getData();
        $recipes->setUser($this->getUser());
          $manager->persist($recipes);
          $manager->flush();
          $this->addFlash(
            'success',
            'votre $recipes a ete cree et success!'
        );
        return $this->redirectToRoute('recipes.index');

    }
    return $this->render('pages/recipes/new.html.twig',[ 
          'form' => $form->createView()
        ]
    
   ); 
}
//edit updat modificacion
#[Security("is_granted('ROLE_USER') and user == recipes.getUser()")]
#[Route("/recipes/edit/{id}", name:"recipes.edit", methods: ['GET', 'POST'])]
public function edit(Recipes $recipes , Request $request ,EntityManagerInterface $manager  ):Response {
    $form = $this->createForm(RecipesType::class, $recipes);
    
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
       
        $recipes = $form->getData();
          $manager->persist($recipes);
          $manager->flush();
          $this->addFlash(
            'success',
            'votre recipes a ete modifie et success!'
        );
        return $this->redirectToRoute('recipes.index');
}
    return $this->render('pages/recipes/edit.html.twig' ,[
        'form' => $form->createView()
    ]
    );


}

// delect 
#[Route("/recipes/delet/{id}", name:"recipes.delet", methods: [  'Get','POST'])]
public function delete(recipes $recipes, Request $request ,EntityManagerInterface $manager ):Response{

   if(!$recipes){
    $this->addFlash(
        'success',
        'L\ ingredient en question n \a pas ete trouve!'

    );
    return $this->redirectToRoute('recipes.index');
   }
$manager->remove($recipes);
$manager->flush();

$this->addFlash(
    'success',
    'votre recipes a ete supprimer et success!'

);
return $this->redirectToRoute('recipes.index');
}


}
