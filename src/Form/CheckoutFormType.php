<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\Shipping;
use App\Repository\ShippingRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('shipping', EntityType::class, [
                'class' => Shipping::class,
                'choice_label' => 'name',
                'query_builder' => function (ShippingRepository $shippingRepository) {
                    return $shippingRepository->createQueryBuilder('s')
                        ->andWhere('s.active = 1')
                        ;
                },
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('promotion_code', TextType::class, [
                'required' => false,
            ])
        ;
    }
}
