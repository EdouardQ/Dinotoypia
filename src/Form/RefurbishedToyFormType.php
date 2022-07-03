<?php

namespace App\Form;

use App\Entity\RefurbishedToy;
use App\Entity\ToyCondition;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;

class RefurbishedToyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez renseigner le nom de votre jouet"
                    ]),
                ]
            ])
            ->add('image', FileType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez nous envoyer une image du jouet sous format .jpeg, .png ou .svg"
                    ]),
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/svg+xml',
                            'image/png'
                        ],
                        'mimeTypesMessage' => "Veuillez nous envoyer une image du jouet sous format .jpeg, .png ou .svg",
                        'maxSize' => '6M',
                        'maxSizeMessage' => "La taille du fichier est trop grosse ({{ size }} {{ suffix }}). La taille maximum est de {{ limit }} {{ suffix }}."
                    ]),
                ]
            ])
            ->add('toyCondition', EntityType::class, [
                'class' => ToyCondition::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Accepter les conditions',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RefurbishedToy::class,
        ]);
    }
}
