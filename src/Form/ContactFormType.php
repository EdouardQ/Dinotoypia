<?php

namespace App\Form;

use App\Entity\ContactSubject;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une adresse email valide'
                    ]),
                    new Email([
                        'message' => 'Veuillez saisir une adresse email valide',
                        'mode' => 'html5',
                    ])
                ]
            ])
            ->add('subject', EntityType::class, [
                'class' => ContactSubject::class,
                'choice_label' => 'subject'
            ])
            ->add('text', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez laisser un message dans la prise de contact"
                    ])
                ]
            ])
        ;
    }
}