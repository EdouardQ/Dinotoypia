<?php

namespace App\Controller;

use App\Manager\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    private OrderManager $orderManager;

    public function __construct(OrderManager $orderManager)
    {
        $this->orderManager = $orderManager;
    }

    #[Route('/checkout', name: 'payment.checkout')]
    public function checkout(): Response
    {
        $order = $this->orderManager->getCurrentOrder();

        return $this->render('payment/checkout.html.twig', [
            'order' => $order
        ]);
    }
}
