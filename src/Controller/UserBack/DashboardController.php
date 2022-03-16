<?php

namespace App\Controller\UserBack;

use App\Entity\Image;
use App\Entity\Product;
use App\Entity\ProductCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/jurassicback', name: 'user_back')]
    public function index(): Response
    {
        return $this->render('user_back/dashboard/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Jurassicback')
            ->setFaviconPath('img/logo_dinotoypia_32.png')
            ->renderContentMaximized()
            //->renderSidebarMinimized()
            ;
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::section('Produits'),
            MenuItem::linkToCrud('Produits', 'fa fa-tags', Product::class),
            MenuItem::linkToCrud('Catégories', 'fa fa-tags', ProductCategory::class),
            MenuItem::linkToCrud('Images', 'fa fa-tags', Image::class),

        ];
    }
}
