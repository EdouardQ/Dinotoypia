<?php

namespace App\Controller\UserBack;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
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
            ->setSearchFields(['name', 'description', 'category.name'])
            ->setDateFormat('d-m-Y')
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            ->setEntityPermission('ROLE_DEV')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Nom'),
            TextField::new('urlName')->setLabel('url'),
            TextareaField::new('description')->setLabel('Description'),
            MoneyField::new('price')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->setLabel('Prix'),
            IntegerField::new('stock')->setLabel('Stock'),
            IntegerField::new('id')
                ->setLabel('Retirer 1 Unité')
                ->setTemplatePath('user_back/product_crud/updating_stock_field.html.twig')
                ->onlyOnIndex(),
            AssociationField::new('category')->setLabel('Catégorie(s)')->onlyOnForms(),
            ArrayField::new('category',)->setLabel('Catégorie(s)')->onlyOnIndex(),
            BooleanField::new('visible')
                ->setLabel('Visible')
                ->setFormTypeOption('disabled', 'disabled')
                ->onlyOnIndex(),
            BooleanField::new('visible')
                ->setLabel('Visible')
                ->hideOnIndex(),
        ];
    }
}
