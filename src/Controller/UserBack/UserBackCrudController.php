<?php

namespace App\Controller\UserBack;

use App\Entity\UserBack;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserBackCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserBack::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['email', 'roles', 'firstName', 'lastName'])
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email'),
            TextField::new('firstName')->setLabel('Prénom'),
            TextField::new('lastName')->setLabel('Nom'),
            TextField::new('password')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options'  => ['label' => 'Mot de Passe'],
                    'second_options' => ['label' => 'Confirmation du Mot de Passe'],
                ])
                ->onlyWhenCreating(),
            ArrayField::new('roles')->onlyOnIndex()->setLabel('Roles'),
            ChoiceField::new('roles')
                ->setChoices([
                    //'UserBack' => "ROLE_USERBACK",
                    'Admin' => "ROLE_ADMIN",
                ])
                ->allowMultipleChoices()
                ->onlyOnForms(),
            AssociationField::new('createdBy')->onlyOnIndex()->setLabel('Créé par'),
            DateField::new('createdAt')->onlyOnIndex()->setLabel('Créé le')
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $userBack = new UserBack();
        $userBack->setCreatedBy($this->getUser());
        $userBack->setCreatedAt(new \DateTimeImmutable());

        return $userBack;
    }
}
