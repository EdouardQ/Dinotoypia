<?php

namespace App\Controller\UserBack;

use App\Entity\RefurbishState;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RefurbishStateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RefurbishState::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['name', 'code'])
            ->setEntityLabelInSingular('État du reconditionnement')
            ->setEntityLabelInPlural('États du reconditionnement')
            ->setEntityPermission('ROLE_DEV')
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::DELETE)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Nom'),
            TextField::new('code')->setLabel('Code')
        ];
    }
}
