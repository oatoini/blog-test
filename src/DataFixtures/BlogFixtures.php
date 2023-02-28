<?php
namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory as Faker;
use Symfony\Component\String\Slugger\AsciiSlugger as Slugger;

class BlogFixtures extends Fixture{    

    private UserPasswordHasherInterface $userPasswordHasher;   
     
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)    
    {        
        $this->passwordHasher = $userPasswordHasher;    

    }    public function load(ObjectManager $manager): void    
    {        
        
        $faker = Faker::create('fr_FR');        
        $slugger = new Slugger();      

        // Admin's creation      
        $admin = new User();        
        $admin            
            ->setEmail('admin@email.com')            
            ->setUsername($faker->lastName()."".$faker->firstName()."".rand(0,99)."ADMIN")            
            ->setRoles(['ROLE_ADMIN'])            
            ->setIsActive(true)            
            ->setCreatedAt(new \DateTimeImmutable("now")) 
            ->setUpdatedAt(new \DateTime())    
        ;        

        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));        
        $manager->persist($admin); 
        
        
        // Author's creation        
        $author = new User();        
        $author            
            ->setEmail('author@email.com')            
            ->setUsername($faker->lastName()."".$faker->firstName()."".rand(0,99)."AUTHOR")            
            ->setRoles(['ROLE_AUTHOR'])            
            ->setIsActive(true)            
            ->setCreatedAt(new \DateTimeImmutable("now"))    
            ->setUpdatedAt(new \DateTime())     ;        
        $author->setPassword($this->passwordHasher->hashPassword($author, 'author'));        
        $manager->persist($author);        
        
        // USERS FACTORY        
        $users = [];        
        for ($i=0; $i < 48; $i++) {            
            $user = new User();            
            $user                
                ->setEmail($faker->email())                
                ->setUsername($faker->lastName()."".$faker->firstName()."".rand(0,99))                
                ->setIsActive(true)                
                ->setCreatedAt(new \DateTimeImmutable()) 
                ->setUpdatedAt(new \DateTime())           
            ;            
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));            
            $manager->persist($user);            
            $users []= $user;        }        
            
            // CATEGORY FACTORY        
            $categories= [];        
            for($i=0; $i < 10; $i++) {            
                $category = new Category();            
                $category->setName($faker->word())                
                ->setCreatedAt(new \DateTimeImmutable())                
                ->setColor($faker->safeHexColor()) 
                ->setUpdatedAt(new \DateTime())           
                ;            
                $manager->persist($category);            
                $categories []= $category;        
            }        
            $manager->flush();        
            
            // ARTICLE FACTORY        
            $articles = [];        
            for ($i=0; $i < 100; $i++) {            
                $article = new Article();            
                $title = $faker->word();            
                $article->setTitle($title)                
                    ->setContent($faker->text())                
                    ->setCreatedAt(new \DateTimeImmutable())      
                    ->setUpdatedAt(new \DateTime())         
                    ->setSlug($slugger->slug($title)->toString())                
                    ->setAuthor($author)                
                    ->setStatus(1)                
                    ->setCategory($categories[rand(0,9)])   
                    ->setFeaturedImage($faker->imageUrl(360,360, 'articles', true))         
                ;            
                
                $articles[]= $article;            
                $manager->persist($article);        
            }        $manager->flush();        
            
            // COMMENT FACTORY        
            for ($i=0; $i < 300; $i++) {            
                $comment = new Comment();            
                $comment->setAuthor($users[rand(0,47)])                
                    ->setArticle($articles[rand(0,99)])                
                    ->setContent($faker->text())                
                    ->setCreatedAt(new \DateTimeImmutable())             
                    ->setIsActive(true)            
                ;            
                $manager->persist($comment);        
            }        
            $manager->flush();    
        }}