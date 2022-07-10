<?php

namespace App\Controller\UserBack;

use App\Entity\Customer;
use App\Entity\Image;
use App\Entity\Log;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\PromotionCode;
use App\Entity\RefurbishedToy;
use App\Entity\RefurbishState;
use App\Entity\Shipping;
use App\Entity\State;
use App\Entity\ToyCondition;
use App\Entity\UserBack;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/jurassicback', name: 'user_back')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(OrderCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Jurassicback')
            ->setFaviconPath('img/logo_dinotoypia_32.png')
            ->renderContentMaximized()
            ;
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::section('Commandes'),
            MenuItem::linkToCrud('Commandes', 'fa fa-cart-plus', Order::class),
            MenuItem::linkToCrud('Objects des commandes', 'fa fa-cart-plus', OrderItem::class),
            MenuItem::linkToCrud('États', 'fa fa-cart-plus', State::class)->setPermission('ROLE_DEV'),
            MenuItem::linkToCrud("Mode d'expédition", 'fa fa-cart-plus', Shipping::class)->setPermission('ROLE_ADMIN'),

            MenuItem::section('Produits')->setPermission('ROLE_ADMIN'),
            MenuItem::linkToCrud('Produits', 'fa fa-tags', Product::class)->setPermission('ROLE_ADMIN'),
            MenuItem::linkToCrud('Catégories', 'fa fa-tags', ProductCategory::class)->setPermission('ROLE_ADMIN'),
            MenuItem::linkToCrud('Images', 'fa fa-tags', Image::class)->setPermission('ROLE_ADMIN'),

            MenuItem::section('Reconditionnement'),
            MenuItem::linkToCrud('Jouets reconditionnés', 'fa fa-tags', RefurbishedToy::class),
            MenuItem::linkToCrud('États', 'fa fa-tags', RefurbishState::class)->setPermission('ROLE_DEV'),
            MenuItem::linkToCrud('Condition', 'fa fa-tags', ToyCondition::class)->setPermission('ROLE_DEV'),

            MenuItem::section(""), // to keep empty
            MenuItem::linkToCrud('Codes promo', 'fa fa-gift', PromotionCode::class),

            MenuItem::section('Admin')->setPermission('ROLE_ADMIN'),
            MenuItem::linkToCrud('Clients', 'fa fa-user', Customer::class)->setPermission('ROLE_ADMIN'),
            MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', UserBack::class)->setPermission('ROLE_ADMIN'),
            MenuItem::linkToCrud('Logs', 'fa fa-address-card-o', Log::class)->setPermission('ROLE_ADMIN'),
        ];
    }
}
