<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddToCartFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("quantity", ChoiceType::class, [
                'multiple' => false,
                'expanded' => false,
                'choices' => $this->getMaxQuantity($options['quantity']),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'quantity' => null,
        ]);
    }

    private function getMaxQuantity(int $stock): array
    {
        $max = min($stock, 5);
        $quantity = [];
        for ($i=1; $i<$max+1; $i++) {
            $quantity[$i] = $i;
        }
        return $quantity;
    }
}
