<?php

namespace App\Controller\UserBack;

use App\Entity\GiftCodeToCustomer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class GiftCodeToCustomerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GiftCodeToCustomer::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['customer.firstName', 'customer.lastName', 'giftCode.label', 'numberUsed'])
            ->setEntityLabelInSingular("Attribution d'un code cadeau")
            ->setEntityLabelInPlural("Attribution des codes cadeau")
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('customer')->setLabel('Client'),
            AssociationField::new('giftCode')->setLabel('Code cadeau'),
            IntegerField::new('numberUsed')->setLabel("Nombre d'utilisation")->hideWhenCreating(),
        ];
    }
}
