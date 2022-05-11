<?php

namespace App\Controller\UserBack;

use App\Entity\Shipping;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ShippingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Shipping::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['name', 'fee'])
            ->setEntityLabelInSingular("Mode d'expédition")
            ->setEntityLabelInPlural("Modes d'expédition")
            ->setEntityPermission('ROLE_ADMIN')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Nom')->hideWhenUpdating(),
            MoneyField::new('fee')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->setLabel('Frais')
                ->hideWhenUpdating(),
            BooleanField::new('active')->setLabel('Active')->hideWhenCreating(),
            IntegerField::new('deliveryEstimateMinimum')
                ->setLabel('Temps de livraison minimum')
                ->hideWhenUpdating(),
            IntegerField::new('deliveryEstimateMaximum')->setLabel('Temps de livraison maximum')->hideWhenUpdating(),
        ];
    }
}
