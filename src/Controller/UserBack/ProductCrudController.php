<?php

namespace App\Controller\UserBack;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['label', 'description'])
            ->setDateFormat('d-m-Y')
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('label')->setLabel('Nom'),
            TextareaField::new('description'),
            MoneyField::new('price')->setCurrency('EUR')->setStoredAsCents(false),
            AssociationField::new('category'),
        ];
    }
}
