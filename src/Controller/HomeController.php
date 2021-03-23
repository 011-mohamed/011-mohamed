<?php

namespace App\Controller;


use App\Controller\HomeController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class HomeController extends AbstractController {

    /**
     * @route("/hello/{prenom}/{age}" , name="hello")
     * @route("/hello" , name="hello_base")
     * @route("/hello/{prenom}", name="hello_prenom")
     * 
     * MONTRE la page qui dit bonjour
     */
 
    public function hello($prenom = "anonyme" , $age = 0) {
        return $this->render(
            'hello.html.twig',
            [
               'prenom'=> $prenom ,
               'age' => $age
            ]
            );
    }

    /**
     * @Route("/", name="homepage")
     */
        public function home(){
            $prenom = ["SALEM " => 21 , "KHEMIR"=> 52 , "BOUZEKRI" => 31] ; 
            return $this->render(
                'home.html.twig',
                ['title'=>"MOHAMED KHALIL SALEM",
                'age'=> 21,
                'tableau' => $prenom 
                ]
            );
        }

}



?>