<?php

namespace App\Controller;

use App\Entity\Sources;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SourcesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sources::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::BATCH_DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::DETAIL, 'SOURCES_VIEW')
            ->setPermission(Action::EDIT, 'SOURCES_ADMIN')
            ->setPermission(Action::INDEX, 'ROLE_USER')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            AssociationField::new('sensor')->setPermission('ROLE_ADMIN'),
            AssociationField::new('AdminUser')->setPermission('ROLE_ADMIN'),
            AssociationField::new('ViewUser')->setPermission('ROLE_ADMIN'),
            AssociationField::new('hourlyFlow')->onlyOnDetail(),
            AssociationField::new('dailyFlow')->onlyOnDetail(),
            DateTimeField::new('sensor.lastSeen')->hideOnForm()->setTemplatePath('CustomFields/date_since.html.twig'),
        ];
    }
}
