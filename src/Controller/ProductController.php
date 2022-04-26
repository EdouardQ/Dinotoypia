<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Manager\OrderManager;
use Doctrine\ORM\EntityManagerInterface;
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
    public function addToOrder(Product $product, EntityManagerInterface $entityManager): Response
    {
        $this->orderManager->createOrderItem($product);


        $response = $this->redirectToRoute('product.index', ['urlName' => $product->getUrlName()]);
        $response->headers->setCookie($this->orderManager->createQuantityCookie());
        $response->send();
    }
}
