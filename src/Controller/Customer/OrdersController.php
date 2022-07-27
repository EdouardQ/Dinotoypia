<?php

namespace App\Controller\Customer;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
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

    #[Route('/orders/invoice/{id}', name: 'customer.orders.invoice')]
    public function invoice(Order $order): Response
    {
        if ($order->getCustomer() !== $this->getUser()) {
            return $this->redirectToRoute('customer.homepage.index');
        }

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);
        $html = $this->renderView('order/order_pdf.html.twig', [
            'order' => $order,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $dompdf->stream('facture_' . $order->getId() . ".pdf", [
            "Attachment" => false
        ]);

        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
