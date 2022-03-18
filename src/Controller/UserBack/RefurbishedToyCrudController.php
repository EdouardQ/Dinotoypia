<?php

namespace App\Controller\UserBack;

use App\Entity\RefurbishedToy;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RefurbishedToyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RefurbishedToy::class;
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
