<?php

namespace App\Controller\Customer;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/my-account')]
class OrdersController extends AbstractController
{
    #[Route('/orders', name: 'customer.orders.index')]
    public function index(OrderRepository $orderRepository): Response
    {
       return $this->render('customer/order/index.html.twig', [
            'orders' => $orderRepository->findCompleteOrdersForCustomer($this->getUser())
        ]);
    }
}
