<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('lastName', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('age', IntegerType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'single_text', // pour un champ de sélection de date simplifié
                'format' => 'yyyy-MM-dd', // format de la date
                'attr' => ['class' => 'form-control'],
            ])
            ->add('medicalHistory', TextType::class, [
                'required' => false, // rendre ce champ optionnel
                'attr' => ['class' => 'form-control'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
