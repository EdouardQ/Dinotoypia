<?php

namespace App\Controller\UserBack;

use App\Entity\Image;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Image::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['name'])
            ->setEntityLabelInSingular('Image')
            ->setEntityLabelInPlural('Images')
            ->setEntityPermission('ROLE_DEV')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Nom'),
            ImageField::new('filename')
                ->setBasePath('img/products/')
                ->setUploadDir('public/img/products/')
                ->setUploadedFileNamePattern('[name]_[timestamp].[extension]')
                ->setLabel('Fichier'),
            AssociationField::new('product')->setLabel('Produit'),
        ];
    }
}
