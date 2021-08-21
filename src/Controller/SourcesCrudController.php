<?php

namespace App\Controller;

use App\Entity\Sources;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SourcesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sources::class;
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
