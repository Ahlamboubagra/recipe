<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\User;

use App\Entity\Contact;
use App\Entity\Mark;
use App\Entity\Recipes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{

private Generator $faker;

public function __construct(){
    $this->faker = Factory::Create('fr_FR');

}


    public function load(ObjectManager $manager): void
    {
      
// users
$users = [];
$admin = new user();
$admin ->setFullName('Administrateur de SymRecipe')
       ->setPseudo('null')
       ->setEmail('admin@symrecipes.fr')
        ->setRoles(['Role_user','Role_Admin'])
        ->setPlainPassword('password');
     $users[] =$admin;
     $manager->persist($admin);
     for($i=0;$i<10;$i++){
    $user = new User();
    $pseudo = mt_rand(0, 1) == 1 ? $this->faker->firstName() : 'default_pseudo';
    
    $user->setFullName($this->faker->name())
        ->setPseudo($pseudo)
        ->setRoles(['roles_user'])
        ->setEmail($this->faker->email())
        ->setPlainPassword('password');
    $users[]=$user;
    $manager->persist($user);


}



        // ingrediants
        $ingredients = [];
        for ($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient
                ->setNom($this->faker->word())
                ->setPrice(mt_rand(0, 100))
                ->setUser($users[mt_rand(0, count($users) - 1)]);
            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        
            // $product = new Product();
            // $manager->persist($product);
        }
        
        // $product = new Product();
        // $manager->persist($product);

       
    
// Recipes

$recipes = [];
for($j = 0; $j < 50; $j++){
    $recipe = new Recipes();
$recipe->setName($this->faker->word())
->setTime(mt_rand(0, 1) == 1 ? mt_rand(0, 1440) : random_int(1, 1440))

->setNbpersonne(mt_rand(0, 1) == 1 ? mt_rand(0, 50) : mt_rand(1, 50))
->setDifficulte(mt_rand(0, 5))
->setDiscription($this->faker->text(300))
->setPrice(mt_rand(0, 1) == 1 ? mt_rand(0, 1000) : random_int(1, 1000))
->setIsFavorite(mt_rand(0,1)==1 ? true:false)
->setIsPublic(mt_rand(0,1)==1 ? true:false)
 ->setUser($users[mt_rand(0,count($users) - 1)]);

for ($k = 0; $k < mt_rand(5, 15); $k++) {
   
    $recipe->addIngredient($ingredients[mt_rand(0,count($ingredients) - 1)]);
}
$recipes[] = $recipe;
$manager->persist($recipe); 
}
// Marks 
foreach($recipes as $recipe) {
   for($i=0;$i< mt_rand(0,4);$i++){
    $mark = new Mark();
    $mark->setMark(mt_rand(1,5))
        ->setUser($users[mt_rand(0, count($users) - 1)])
        ->setRecipe($recipe);
     $manager->persist($mark);
    }
}





// Contact 
for ($i = 0; $i < 5; $i++) {
    $contact = new Contact();
    $contact->setFullName($this->faker->name())
            ->setSubject('Demande n' . ($i + 1))
            ->setEmail($this->faker->email())
            ->setMessage($this->faker->text());
    $manager->persist($contact);
}
$manager->flush();



    }
}




