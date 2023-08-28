<?php
namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\RecipesRepository;
class HomeController extends AbstractController
{
    #[Route('/','home.index',methods:['GET'])]
    public function index(RecipesRepository $repository):Response
    {
      return $this->render('pages/home.html.twig',[
'recipes'=>$repository->findPublicRecipe(3)

      ]);
    }
}