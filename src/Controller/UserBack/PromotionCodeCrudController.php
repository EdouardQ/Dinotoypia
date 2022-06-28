<?php

namespace App\Controller\UserBack;

use App\Entity\PromotionCode;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PromotionCodeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PromotionCode::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['name', 'code', 'createdAt', 'expiresAt', 'type', 'amount', 'amountType', 'refurbishedToy.name', 'refurbishedToy.barCodeNumber', 'refurbishedToy.customer.firstName', 'refurbishedToy.customer.lastName', 'useLimit', 'useLimitPerCustomer'])
            ->setEntityLabelInSingular('Code promo')
            ->setEntityLabelInPlural('Codes promo')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Label'),
            ChoiceField::new('type')
                ->setChoices([
                    'Bon d\'achat' => 'voucher',
                    'Code Promo' => 'giftcode',
                ])
                ->setLabel('Type'),
            AssociationField::new('refurbishedToy')->setLabel('Jouet'),
            TextField::new('code')->setLabel('Code'),
            MoneyField::new('amount')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->setLabel('Montant'),
            ChoiceField::new('amountType')
                ->setChoices([
                    'Montant' => 'amount',
                    'Pourcentage' => 'percentage',
                ])
                ->setLabel('Type de réduction'),
            DateField::new('expiresAt')->setLabel('Expire le'),
            IntegerField::new('useLimit')->setLabel("Nombre d'utilisation maximal"),
            IntegerField::new('useLimitPerCustomer')->setLabel("Nombre d'utilisation maximal par Client"),
            MoneyField::new('minimumAmount')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->setLabel('Montant minimum'),
            BooleanField::new('firstTimeTransaction')
                ->setLabel('Utilisable uniquement pour la 1ère commande')
                ->onlyOnIndex()
                ->setFormTypeOption('disabled', 'disabled'),
            BooleanField::new('firstTimeTransaction')
                ->setLabel('Utilisable uniquement pour la 1ère commande')
                ->hideOnIndex(),
            TextareaField::new('comments')->setLabel('Commentaire(s)')
        ];
    }
}
