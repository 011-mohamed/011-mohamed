<?php

namespace App\Form;

use App\Entity\Comment;
use App\form \ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           
            ->add('rating', IntegerType::class,$this->getConfiguration("Note /5",
            "Veuillez indiquer votre note de 0 a 5",
            [
                'attr'=>[
                    'min'=>0,
                    'max'=>5,
                    'step'=>1
                ]
                
            ]))
            ->add('content', TextareaType::class, $this->getConfiguration("Votre avis",
                "N'hezitez pas a etre tres précis !"))
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
