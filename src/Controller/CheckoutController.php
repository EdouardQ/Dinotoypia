<?php

namespace App\Controller;

use App\Manager\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'checkout.index')]
    public function index(OrderManager $orderManager): Response
    {
        return $this->render('checkout/index.html.twig', [
            'order' => $orderManager->createCheckout(),
        ]);
    }
}
