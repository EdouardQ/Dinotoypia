<?php

namespace App\Controller\UserBack;

use App\Entity\GiftCode;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GiftCodeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GiftCode::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['name', 'code', 'createdAt', 'expiresAt', 'numberUsesLimit'])
            ->setEntityLabelInSingular('Code cadeau')
            ->setEntityLabelInPlural('Codes cadeau')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Label'),
            TextField::new('code')->setLabel('Code'),
            TextField::new('amount')->setLabel('Montant'),
            ChoiceField::new('type')
                ->setChoices([
                    'pourcentage' => 'percentage',
                    'montant' => 'amount'
                ])
                ->setLabel('Type'),
            DateField::new('createdAt')->setLabel('Crée le')->onlyOnIndex(),
            DateField::new('expiresAt')->setLabel('Expire le'),
            IntegerField::new('numberUsesLimit')->setLabel("Nombre d'utilisation maximal")->hideOnIndex(),
            IntegerField::new('numberRemainingUses')->setLabel("Nombre d'utilisations restantes")->onlyOnIndex(),
        ];
    }
}
