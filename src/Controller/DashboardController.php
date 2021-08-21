<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Suivi des sources');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::section('Administration');

        yield MenuItem::linkToLogout('Déconnexion', 'fa fa-exit');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}