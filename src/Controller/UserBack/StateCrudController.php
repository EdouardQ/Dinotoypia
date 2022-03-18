<?php

namespace App\Controller\UserBack;

use App\Entity\State;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return State::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['label'])
            ->setEntityLabelInSingular('État')
            ->setEntityLabelInPlural('États')
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
            TextField::new('label')->setLabel('Nom'),
            TextField::new('code')->setLabel('Code')
        ];
    }
}
