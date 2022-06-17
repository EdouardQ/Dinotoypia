<?php

namespace App\Controller\UserBack;

use App\Entity\Log;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Log::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['user', 'entity', 'entityId', 'action'])
            ->setEntityLabelInSingular('Log')
            ->setEntityLabelInPlural('Logs')
            ->setEntityPermission('ROLE_ADMIN')
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('user')->setLabel('Utilisateur'),
            TextField::new('entity')->setLabel('Entité'),
            IdField::new('entityId')->setLabel("Id de l'entité"),
            DateTimeField::new('logedAt')->setLabel("fait le"),
        ];
    }
}
