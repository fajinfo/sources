<?php

namespace App\Controller;

use App\Entity\Sensors;
use App\Repository\SensorsUplinksRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SensorsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sensors::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityPermission('ROLE_ADMIN');
    }

    public function configureFields(string $pageName): iterable
    {
        $fields =  [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('source')->hideOnForm(),
            TextField::new('devEui'),
            DateTimeField::new('lastSeen')->hideOnForm()->setTemplatePath('CustomFields/date_since.html.twig'),
            PercentField::new('lastBatteryPercent')->hideOnForm(),
            FormField::addPanel('Uplinks')->onlyOnDetail(),
            AssociationField::new('uplinks')->onlyOnDetail()->setTemplatePath('CustomFields/associationTable.html.twig')->setQueryBuilder(SensorsUplinksRepository::getForTable()),
        ];
        return $fields;
    }
}
