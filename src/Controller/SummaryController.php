<?php

namespace App\Controller;

use App\Manager\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SummaryController extends AbstractController
{
    #[Route('/summary', name: 'summary.index')]
    public function index(OrderManager $orderManager): Response
    {
        return $this->render('summary/index.html.twig', [
            'order' => $orderManager->createCheckout(),
        ]);
    }
}
