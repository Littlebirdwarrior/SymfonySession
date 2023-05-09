<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Trainer;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'label'=>'Intitule'
            ])
            ->add('startDate',DateType::class,[
                'label' => 'Date de Debut',
                'widget' => 'single_text',
            ])
            ->add('endDate',DateType::class,[
                'label' => 'Date de Fin',
                'widget' => 'single_text',
            ])
            ->add('nbPlace',IntegerType::class,[
                'label'=>'Nombre de places',
            ])
            ->add('image',TextType::class,[
                'label'=>'url image'
            ])
            ->add('formation',EntityType::class,[
                'class'=>Formation::class,
                'choice_label'=> 'title',
            ])
            ->add('trainer',EntityType::class,[
                'class'=>Trainer::class,
                'choice_label'=> 'firstname',
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
