<?php

namespace App\Controller;

use App\Entity\OrderItem;
use App\Entity\Product;
use App\Form\AddToCartFormType;
use App\Manager\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Product $product, Request $request): Response
    {
        $form = $this->createForm(AddToCartFormType::class, null, [
            'quantity' => $product->getStock(),
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($product->getStock() > 0) {
                $this->orderManager->addItemToOrderSession($product->getId(), $form->getData()['quantity']);
                $this->orderManager->updateCart();
            }
            else {
                $this->addFlash('addToOrderNotice', "Le produit est actuellement en rupture de stock, veuillez réessayer ultérieurement");
            }
            return $this->redirectToRoute('product.index', ['urlName' => $product->getUrlName()]);
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    #[Route('/remove_to_order/{id}', name: 'product.remove_to_order')]
    public function removeToOrder(Product $product): Response
    {
        $this->orderManager->removeItemToOrderSession($product->getId());
        $this->orderManager->updateCart();
        return $this->redirectToRoute('homepage.summary');
    }
}
