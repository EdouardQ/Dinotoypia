<?php

namespace App\Controller\Customer;

use App\Entity\State;
use App\Form\RelayPointFormType;
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
        
    #[Route('/delivery', name: 'customer.payment.delivery')]
    public function delivery(EntityManagerInterface $entityManager, Request $request): Response
    {
        if (empty($this->orderManager->getOrderSession())) {
            return $this->redirectToRoute('checkout.index');
        }

        $order = $this->orderManager->getOrder($this->getUser());

        // if the order is empty of orderItems
        if (!$order->hasOrderItems()) {
            return $this->redirectToRoute('homepage.index');
        }

        $form = $this->createForm(RelayPointFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order->setRelayPointId($form->getData()['targetWidget']);
            // set new state of the order
            $order->setState($entityManager->getRepository(State::class)->findOneBy(["code" => "in_payment"]));
            $entityManager->flush();

            return $this->redirectToRoute('customer.payment.payment_process');
        }

        return $this->render('customer/payment/delivery.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/payment-process', name: 'customer.payment.payment_process')]
    public function paymentProcess(EntityManagerInterface $entityManager, StripeService $stripeService): Response
    {
        if (empty($this->orderManager->getOrderSession())) {
            return $this->redirectToRoute('checkout.index');
        }

        $order = $this->orderManager->getOrder($this->getUser());

        $this->orderManager->checkAndUpdateOrder($order);

        // if the order is empty of orderItems
        if (!$order->hasOrderItems()) {
            return $this->redirectToRoute('homepage.index');
        }

        $stripeSession = $stripeService->createSession($order);

        $order->setPaymentStripeId($stripeSession->payment_intent);
        $entityManager->flush();

        return $this->redirect($stripeSession->url);
    }

    #[Route('/payment-succeeded', name: 'customer.payment.payment_succeeded')]
    public function paymentSucceeded(EntityManagerInterface $entityManager, StripeService $stripeService): Response
    {
        $order = $this->orderManager->getOrder($this->getUser());

        // if the order is empty of orderItems and has the right state
        if (!$order->hasOrderItems() && !$order->inPaymentState()) {
            return $this->redirectToRoute('homepage.index');
        }

        $order->setState($entityManager->getRepository(State::class)->findOneBy(["code" => "in_delevery"]));
        $order->setEstimatedDelivery($order->calculEstimatedDeliveryDateTime());
        $entityManager->flush();

        $this->orderManager->purgeOrderSession();

        return $this->redirectToRoute('homepage.index');
    }

    #[Route('/payment-failed', name: 'customer.payment.payment_failed')]
    public function paymentFailed(EntityManagerInterface $entityManager): Response
    {
        $order = $this->orderManager->getOrder($this->getUser());

        // if the order is empty of orderItems and has the right state
        if (!$order->hasOrderItems() && !$order->inPaymentState()) {
            return $this->redirectToRoute('homepage.index');
        }

        $order->setState($entityManager->getRepository(State::class)->findOneBy(["code" => "pending"]));
        $order->setPaymentStripeId(null);
        $order->setRelayPointId(null);

        $entityManager->flush();

        $this->addFlash('paymentFailedNotice', "Le paiement a échoué.");
        return $this->redirectToRoute('checkout.index');
    }
}
