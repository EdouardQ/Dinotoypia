<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class RelayPointFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('targetWidget', HiddenType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez choisir un point relais sur la carte intéractive."
                    ])
                ]
            ])
        ;
    }
}
