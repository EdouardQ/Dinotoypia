<?php

namespace App\Controller;

use App\Entity\OrderItem;
use App\Entity\Product;
use App\Manager\OrderManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
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
        $order = $this->orderManager->getCurrentOrder();
        $this->orderManager->save($order);

        $orderItem = new OrderItem();
        $orderItem->setProduct($product);
        $orderItem->setPrice($product->getPrice());
        $orderItem->setQuantity(1);
        $orderItem->setOrder($order);

        $entityManager->persist($orderItem);
        $entityManager->flush();

        $response = $this->redirectToRoute('product.index', ['urlName' => $product->getUrlName()]);
        $response->headers->setCookie($this->orderManager->createQuantityCookie());
        $response->send();
    }
}
