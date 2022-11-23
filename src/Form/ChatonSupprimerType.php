<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Chaton;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChatonSupprimerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('OK', SubmitType::class,
                ['label' => 'Supprimer',
                    'attr' => ['class' => 'btn btn-danger btn-lg px-4 gap-3']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chaton::class,
        ]);
    }
}