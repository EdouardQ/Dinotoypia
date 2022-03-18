<?php

namespace App\Controller\UserBack;

use App\Entity\RefurbishState;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RefurbishStateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RefurbishState::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
