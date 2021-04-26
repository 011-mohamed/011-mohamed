<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\form \ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnonceType extends ApplicationType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("titre", "Tapez un super titre pour votre annonce"))

            ->add('slug', TextType::class, $this->getConfiguration("Adresse web", "Tapez l'adresse web (automatique)", [
                "required" => false
            ]))

            ->add('coverImage', UrlType::class, $this->getConfiguration("URL de l'image principale", "Donnez l'adreese d'image"))

            ->add('intoduction', TextType::class, $this->getConfiguration("Introduction", "Donnez une description globale de l'annonce"))

            ->add('content', TextareaType::class, $this->getConfiguration("Description détaillée", "Tapez une bonne description sur votre annonce"))

            ->add('rooms', IntegerType::class, $this->getConfiguration("nombre de chambres", "Le nombre de chambres disponibles"))

            ->add('price', MoneyType::class, $this->getConfiguration("Prix par nuit ", "Indiquez le prix"))

            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add'  => true,
                    'allow_delete' => true 
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
