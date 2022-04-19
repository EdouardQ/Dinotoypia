<?php

namespace App\Controller\UserBack;

use App\Entity\Voucher;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class VoucherCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Voucher::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['customer.firstName', 'customer.lastName', 'name', 'code', 'createdAt', 'expiresOn',])
            ->setEntityLabelInSingular("Bon d'achat")
            ->setEntityLabelInPlural("Bons d'achat")
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('customer')->setLabel('Client'),
            TextField::new('name')->setLabel('Label'),
            MoneyField::new('amount')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->setLabel('Montant'),
            TextField::new('code')->setLabel('Code'),
            DateField::new('createdAt')->setLabel('Crée le')->onlyOnIndex(),
            DateField::new('expiresOn')->setLabel('Expire le')->hideWhenCreating(),
        ];
    }
}
