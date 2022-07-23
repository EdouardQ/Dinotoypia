<?php

namespace App\Controller\Customer;

use App\Entity\PromotionCode;
use App\Entity\Shipping;
use App\Entity\State;
use App\Form\CheckoutFormType;
use App\Manager\OrderManager;
use App\Repository\PromotionCodeRepository;
use App\Service\AddressesService;
use App\Service\MailService;
use App\Service\PromotionCodeService;
use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payment')]
class PaymentController extends AbstractController
{
    private AddressesService $addressesService;
    private OrderManager $orderManager;

    public function __construct(AddressesService $addressesService, OrderManager $orderManager)
    {
        $this->addressesService = $addressesService;
        $this->orderManager = $orderManager;
    }

    #[Route('/checkout', name: 'customer.payment.checkout')]
    public function delivery(Request $request, EntityManagerInterface $entityManager, PromotionCodeService $promotionCodeService): Response
    {
        $order = $this->orderManager->getOrder($this->getUser());

        if ($this->orderManager->checkUpdateAndFixOrder($order)) {
            $this->addFlash('outOfStockNotice', "Une erreur est survenue dans votre commande");
            return $this->redirectToRoute('homepage.summary');
        }

        $form = $this->createForm(CheckoutFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order->setPromotionCode(null);

            $order->setShipping($form->getData()['shipping']);
            if ($order->getShipping()->getName() === "Livraison Mondial Relais") {
                if ($request->request->get('id') === "") {
                    $this->addFlash('mondialRelaisNotice', "Veuillez sélectionner un point relais");
                    return $this->redirectToRoute('customer.payment.checkout');
                }
               $this->addressesService->addDeliveryAddressToOrderByRequest($order, $request, $entityManager);
            }

            if ($form->getData()['promotion_code'] !== null) {
                $promotionCode = $entityManager->getRepository(PromotionCode::class)->findOneBy(['code' => $form->getData()['promotion_code']]);
                if ($promotionCode === null || !$promotionCodeService->checkUseCondition($this->getUser(), $promotionCode)) {
                    $this->addFlash('checkoutNotice', "Code promo invalide ou expiré");
                    return $this->redirectToRoute('customer.payment.checkout');
                }

                $order->setPromotionCode($promotionCode);
            }

            $entityManager->flush();
            return $this->redirectToRoute('customer.payment.payment_process');
        }

        return $this->render('customer/payment/checkout.html.twig', [
            'form' => $form->createView(),
            'order' => $order,
            'shipppingList' => $entityManager->getRepository(Shipping::class)->getShippingDataForCheckout(),
        ]);
    }

    #[Route('/checkout/promocode/{code}', name: 'customer.payment.add_and_check_promocode', defaults: ['code' => null])]
    public function addAndCheckPromoCode($code, EntityManagerInterface $entityManager, PromotionCodeRepository $promotionCodeRepository, PromotionCodeService $promotionCodeService): Response
    {
        $order = $this->orderManager->getOrder($this->getUser());
        // if the route parameter is null
        if (is_null($code)) {
            if ($order->getPromotionCode()) {
                $order->getPromotionCode()->removeOrder($order);
                $entityManager->flush();
                return new Response(json_encode([
                    'code' => 'removed',
                    'message' => "Code promo retiré de votre commande avec succès"
                ]));
            }
            return new Response(json_encode([
                'code' => 'null',
                'message' => "Rien ne s'est passé"
            ]));
        }

        $promotionCode = $promotionCodeRepository->findOneBy(['code' => $code]);
        // if the code doesn't exist
        if (is_null($promotionCode)) {
            return new Response(json_encode([
                'code' => 'not found',
                'message' => "Ce code promo n'éxiste pas"
            ]));
        }

        // if teh code is already added to the order
        if ($order->getPromotionCode() && $order->getPromotionCode() === $promotionCode) {
            return new Response(json_encode([
                'code' => 'aleardy in order',
                'message' => "Ce code promo est déjà affecté à votre commande"
            ]));
        }

        // if the customer is not able to use the code
        if (!$promotionCodeService->checkUseCondition($this->getUser(), $order, $promotionCode)) {
            return new Response(json_encode([
                'code' => 'condition not meet',
                'message' => "Votre commande ne remplie pas les conditions d'utilisation de ce code promo"
            ]));
        }

        $promotionCode->addOrder($order);
        $entityManager->flush();
        return new Response(json_encode([
            'code' => 'add',
            'message' => "Code promo ajouté avec succès"
        ]));
    }

    #[Route('/payment-process', name: 'customer.payment.payment_process')]
    public function paymentProcess(EntityManagerInterface $entityManager, StripeService $stripeService): Response
    {
        if (empty($this->orderManager->getOrderSession())) {
            return $this->redirectToRoute('homepage.summary');
        }

        $order = $this->orderManager->getOrder($this->getUser());

        if ($this->orderManager->checkUpdateAndFixOrder($order)) {
            $this->addFlash('outOfStockNotice', "Une erreur est survenue dans votre commande");
            return $this->redirectToRoute('homepage.summary');
        }

        // if the order is empty of orderItems
        if (!$order->hasOrderItems()) {
            return $this->redirectToRoute('homepage.index');
        }

        $sessionStripe = $stripeService->createSession($order);

        $order->setPaymentStripeId($sessionStripe->payment_intent);
        $entityManager->flush();

        return $this->redirect($sessionStripe->url);
    }

    #[Route('/succeeded-payment', name: 'customer.payment.payment_succeeded')]
    public function paymentSucceeded(EntityManagerInterface $entityManager, StripeService $stripeService, MailService $mailService): Response
    {
        $order = $this->orderManager->getOrder($this->getUser());

        // if the order is empty of orderItems and has the right state
        if (!$order->hasOrderItems() && !$order->inPaymentState()) {
            return $this->redirectToRoute('homepage.index');
        }

        // if the payment is not succeeded, return an error 500
        $stripeService->createBillingAndDeliveryAddresses($order, $this->addressesService, $entityManager);

        $stripeService->checkAfterSucceededPayment($order, $entityManager);

        $this->orderManager->purgeOrderSession();

        $mailService->sendEmailOrder($order);

        return $this->render('order/order_confirm.html.twig');
    }

    #[Route('/failed-payment', name: 'customer.payment.payment_failed')]
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
        return $this->redirectToRoute('homepage.summary');
    }
}
