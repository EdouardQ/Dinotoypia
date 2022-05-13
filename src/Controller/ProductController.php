<?php

namespace App\Controller;

use App\Entity\OrderItem;
use App\Entity\Product;
use App\Manager\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    private OrderManager $orderManager;

    public function __construct(OrderManager $orderManager)
    {
        $this->orderManager = $orderManager;
    }

    #[Route('/{urlName}', name: 'product.index')]
    public function index(Product $product): Response
    {
        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/add_to_order/{id}', name: 'product.add_to_order')]
    public function addToOrder(Product $product): Response
    {
        $this->orderManager->addItemToOrderSession($product->getId());
        $this->orderManager->updateCart();
        return $this->redirectToRoute('product.index', ['urlName' => $product->getUrlName()]);
    }

    #[Route('/remove_to_order/{id}', name: 'product.remove_to_order')]
    public function removeToOrder(Product $product): Response
    {
        $this->orderManager->removeItemToOrderSession($product->getId());
        $this->orderManager->updateCart();
        return $this->redirectToRoute('homepage.summary');
    }
}
