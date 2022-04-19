<?php

namespace App\Controller\UserBack;

use App\Entity\OrderItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class OrderItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderItem::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['order.id', 'product.name'])
            ->setEntityLabelInSingular('Object de commande')
            ->setEntityLabelInPlural('Objects de commande')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('order')->setLabel('Commande'),
            AssociationField::new('product')->setLabel('Produit'),
            MoneyField::new('price')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->setLabel('Prix unitaire')
                ->onlyOnIndex(),
            IntegerField::new('quantity')->setLabel('Quantité')
        ];
    }
}
