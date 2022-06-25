<?php

namespace App\Controller\UserBack;

use App\Entity\RefurbishedToy;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RefurbishedToyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RefurbishedToy::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['name', 'barCodeNumber','customer.firstName', 'customer.lastName', 'createdAt', 'state.name'])
            ->setEntityLabelInSingular('Jouet reconditionné')
            ->setEntityLabelInPlural('Jouets reconditionnés')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Nom'),
            TextField::new('barCodeNumber')->setLabel('Numéro - code barre')->onlyOnIndex(),
            AssociationField::new('customer')->setLabel('Client'),
            DateTimeField::new('createdAt')->setLabel('Créé le')->onlyOnIndex(),
            ImageField::new('image')
                ->setTemplatePath('user_back/refurbished_toy_crud/image_field.html.twig')
                ->setLabel('Image')
                ->onlyOnIndex(),
            AssociationField::new('state')->setLabel('État'),
        ];
    }
}
