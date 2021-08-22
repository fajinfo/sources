<?php

namespace App\Controller;

use App\Entity\Sensors;
use App\Entity\User;
use App\Entity\Sources;
use App\Repository\SourcesRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @var SourcesRepository
     */
    protected $sourcesRepository;

    public function __construct(SourcesRepository $sourcesRepository)
    {
        $this->sourcesRepository = $sourcesRepository;
    }

    /**
     * @Route("/", name="dashboard")
     * @Security("is_granted('ROLE_USER')")
     */
    public function index(): Response
    {
        $sources = $this->sourcesRepository->findForDashboardWithData($this->getUser());
        return $this->render('Dashboard/dashboard.html.twig', array('sources' => $sources));
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Suivi des sources');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addJsFile('assets/chart.min.js');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::linkToCrud('Sources', 'fas fa-tint', Sources::class)->setPermission('ROLE_USER');
        yield MenuItem::section('Administration')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Senseurs', 'fas fa-broadcast-tower', Sensors::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class)->setPermission('ROLE_ADMIN');
    }
}
