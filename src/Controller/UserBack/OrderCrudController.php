<?php

namespace App\Controller\UserBack;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id', 'trackingNumber', 'customer.firstName', 'customer.lastName', 'state.name', 'estimatedDelivery', 'orderItems.product.name'])
            ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Commandes')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('trackingNumber')->setLabel('Numéro de suivi')->hideWhenCreating(),
            AssociationField::new('customer')->setLabel('Client'),
            AssociationField::new('state')->setLabel('État'),
            DateTimeField::new('createdAt')->setLabel("crée le")->onlyOnIndex(),
            DateField::new('estimatedDelivery')->setLabel('Date de livraison estimée')->hideWhenCreating(),
            ArrayField::new('orderItems')
                ->setTemplatePath('user_back/order_crud/order_items_table.html.twig')
                ->setLabel("Produit - Prix unitaire - Quantité")
                ->onlyOnIndex(),
            MoneyField::new('totalPriceOfOrderItems')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->setLabel('Prix total')
                ->onlyOnIndex(),
        ];
    }
}
