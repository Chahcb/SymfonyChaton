<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Chaton;
use App\Entity\Proprietaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChatonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('Sterilise')
            ->add('Photo', FileType::class, array('data_class' => null))
            ->add('Categorie', EntityType::class, [
                'class' => Categorie::class, // choix de la classe liée
                'choice_label' => 'titre', // choix de ce qui sera affiché comme texte
                'multiple' => false,
                'expanded' => false])
            ->add('Proprietaire', EntityType::class, [
                'class' => Proprietaire::class, // choix de la classe liée
                'choice_label' => 'prenom', // choix de ce qui sera affiché comme texte
                'mapped' => false, // pour ajouter un champs qui n'est pas dans la table
                'multiple' => false,
                'expanded' => false])

            ->add('OK', SubmitType::class, ['label' => 'OK',
                'attr' => ['class' => 'btn btn-primary px-5']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chaton::class,
        ]);
    }
}