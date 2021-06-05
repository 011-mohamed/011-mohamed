<?php

namespace App\DataFixtures;

use Faker;



use App\Entity\Ad;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{   private $encodor ;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder =$encoder ;
        
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

          $adminUser = new User();
          $adminUser->setFirstName('Khalil')
                    ->setLastName('Salem')
                    ->setEmail('khalil@symfony.com')
                    ->setIntroduction($faker->sentence())
                    ->setDescription('<p>'.( $faker-> paragraph(3)).'</p>')
                    ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                    ->setPicture('https://randomuser.me/api/portraits/men/42.jpg')
                    ->addUserRole($adminRole);

            $manager->persist($adminUser);


       // gere des utilisateurs
            $users = []; 
            $genres = ['male' ,'female'];
        for ($i=1; $i <= 10 ; $i++) {
            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $picture .=($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user,'password');

             $user ->setFirstName($faker->firstname)
                   ->setLastName($faker->lastname)
                   ->setEmail($faker->email)
                   ->setIntroduction($faker->sentence())
                   ->setDescription('<p>'.( $faker-> paragraph(3)).'</p>')
                   ->setHash($hash)
                   ->setPicture($picture);

                   $manager->persist($user);
                   $users[] =$user;


         }
        // geré des annonces
        for($i=1; $i<=10; $i++)
        {

        $ad = new Ad();

        $title = $faker->sentence();
      
        $coverImage = $faker->imageUrl(1000,350);
        $intoduction = $faker-> paragraph(2);
        $content = '<p>'.( $faker-> paragraph(5)).'</p>';

        $user = $users[mt_rand(0,count($users)-1)];
        $ad ->setTitle($title)
            ->setCoverImage($coverImage)
            ->setIntoduction($intoduction)
            ->setContent($content)
            ->setPrice(mt_rand(40, 200))
            ->setRooms(mt_rand(1,5))
            ->setAuthor($user);
            

        for($j=1;$j<= mt_rand(2, 4); $j++){
            $image = new Image();

            $image->setUrl($faker->imageUrl())
                  ->setCaption($faker->sentence())
                  ->setAd($ad);

            $manager->persist($image);
            
        // Gestion des reservations 
        for ($j=1; $j <= mt_rand(0,10) ; $j++) { 
            $booking = new Booking();

            $createdAt = $faker->dateTimeBetween('-6 months');
            $startDate = $faker->dateTimeBetween('-3 months');

            // gestion de la date de fin
            $duration = mt_rand(3,10);
            $endDate = (clone $startDate)->modify("+$duration days");
            $amount = $ad->getPrice() * $duration;

            $booker = $users[mt_rand(0,count($users)-1)];
            
            $comment = $faker->paragraph();

            $booking->setBooker($booker)
                    ->setAd($ad)
                    ->setStartDate($startDate)
                    ->setEndDate($endDate)
                    ->setCreatedAt($createdAt)
                    ->setAmount($amount)
                    ->setComment($comment) ;

                    $manager->persist($booking);
        }

        }


            $manager->persist($ad);
        }     
        $manager->flush();
    }
}
