<?php

namespace App\Controller\UserBack;

use App\Entity\GiftCode;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
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
            ->setSearchFields(['name', 'code', 'createdAt', 'expiresOn', 'numberUsesLimit'])
            ->setEntityLabelInSingular('Code cadeau')
            ->setEntityLabelInPlural('Codes cadeau')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Label'),
            TextField::new('code')->setLabel('Code'),
            DateField::new('createdAt')->setLabel('Crée le')->onlyOnIndex(),
            DateField::new('expiresOn')->setLabel('Expire le'),
            IntegerField::new('numberUsesLimit')->setLabel("Nombre d'utilisation maximal")->hideOnIndex(),
            IntegerField::new('numberRemainingUses')->setLabel("Nombre d'utilisations restantes")->onlyOnIndex(),
        ];
    }
}
