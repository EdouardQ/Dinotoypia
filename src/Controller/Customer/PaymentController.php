<?php

namespace App\Controller\Customer;

use App\Entity\State;
use App\Form\CheckoutFormType;
use App\Manager\OrderManager;
use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payment')]
class PaymentController extends AbstractController
{
    private OrderManager $orderManager;

    public function __construct(OrderManager $orderManager)
    {
        $this->orderManager = $orderManager;
    }

    #[Route('/checkout', name: 'customer.payment.checkout')]
    public function delivery(Request $request, EntityManagerInterface $entityManager): Response
    {
        $order = $this->orderManager->getOrder($this->getUser());
        $form = $this->createForm(CheckoutFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form->getData()['promotion_code']);
            // create SERVICE
            $order->setShipping($form->getData()['shipping']);
            $entityManager->flush();
            return $this->redirectToRoute('customer.payment.payment_process');
        }

        return $this->render('customer/payment/checkout.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/payment-process', name: 'customer.payment.payment_process')]
    public function paymentProcess(EntityManagerInterface $entityManager, StripeService $stripeService): Response
    {
        if (empty($this->orderManager->getOrderSession())) {
            return $this->redirectToRoute('summary.index');
        }

        $order = $this->orderManager->getOrder($this->getUser());

        $this->orderManager->checkAndUpdateOrder($order);

        // if the order is empty of orderItems
        if (!$order->hasOrderItems()) {
            return $this->redirectToRoute('homepage.index');
        }

        $sessionStripe = $stripeService->createSession($order);

        $order->setPaymentStripeId($sessionStripe->payment_intent);
        $entityManager->flush();

        return $this->redirect($sessionStripe->url);
    }

    #[Route('/payment-succeeded', name: 'customer.payment.payment_succeeded')]
    public function paymentSucceeded(EntityManagerInterface $entityManager, StripeService $stripeService): Response
    {
        $order = $this->orderManager->getOrder($this->getUser());

        // if the order is empty of orderItems and has the right state
        if (!$order->hasOrderItems() && !$order->inPaymentState()) {
            return $this->redirectToRoute('homepage.index');
        }

        $stripeService->createBillingAndDeliveryAddresses($order, $entityManager);
        $this->orderManager->purgeOrderSession();

        return $this->redirectToRoute('homepage.index');
    }

    #[Route('/payment-failed', name: 'customer.payment.payment_failed')]
    public function paymentFailed(EntityManagerInterface $entityManager, StripeService $stripeService): Response
    {
        $order = $this->orderManager->getOrder($this->getUser());

        // if the order is empty of orderItems and has the right state
        if (!$order->hasOrderItems() && !$order->inPaymentState()) {
            return $this->redirectToRoute('homepage.index');
        }

        $order->setState($entityManager->getRepository(State::class)->findOneBy(["code" => "pending"]));
        $order->setPaymentStripeId(null);

        $entityManager->flush();

        $this->addFlash('paymentFailedNotice', "Le paiement a échoué.");
        return $this->redirectToRoute('summary.index');
    }
}
