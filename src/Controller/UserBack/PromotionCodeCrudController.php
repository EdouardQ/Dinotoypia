<?php

namespace App\Controller\UserBack;

use App\Entity\PromotionCode;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
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
            ->setSearchFields(['name', 'code', 'createdAt', 'expiresAt', 'type', 'amount', 'amountType', 'customer', 'stripeId', 'couponStripeId'])
            ->setEntityLabelInSingular('Code promo')
            ->setEntityLabelInPlural('Codes promo')
            ->setPageTitle('index', "<h1>Codes promo</h1><br><a href='https://dashboard.stripe.com/test/coupons' target='_blank'>À gérer au préalable ici</a>")
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
            AssociationField::new('customer')->setLabel('Client'),
            TextField::new('code')->setLabel('Code'),
            TextField::new('stripeId')->setLabel('ID Stripe'),
            TextField::new('couponStripeId')->setLabel('ID coupon Stripe'),
            TextField::new('amount')->setLabel('Montant'),
            ChoiceField::new('amountType')
                ->setChoices([
                    'Montant' => 'amount',
                    'Pourcentage' => 'percentage',
                ])
                ->setLabel('Type de réduction'),
            DateField::new('expiresAt')->setLabel('Expire le'),
            IntegerField::new('useLimit')->setLabel("Nombre d'utilisation maximal"),
            TextareaField::new('comments')->setLabel('Commentaire(s)')
        ];
    }
}
