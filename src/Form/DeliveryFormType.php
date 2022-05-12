<?php

namespace App\Form;

use App\Entity\Shipping;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class DeliveryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', EntityType::class, [
                'class' => Shipping::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false,
            ])
            // Colissimo && Chronopost
            ->add('address', TextType::class, [
                'required' => false,
            ])
            ->add('post_code', TextType::class, [
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'required' => false,
            ])
            // Modial Relay
            ->add('relais_id', HiddenType::class, [
                'required' => false,
            ])
            ->add('relais_address', HiddenType::class, [
                'required' => false,
            ])
            ->add('relais_post_code', HiddenType::class, [
                'required' => false,
            ])
            ->add('relais_city', HiddenType::class, [
                'required' => false
            ])
        ;
    }
}