<?php

namespace App\Controller\UserBack;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('jurassicback/product')]
class ProductController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    #[Route('/', name: 'jurassicback.product.index')]
    public function index(): Response
    {
        return $this->render('user_back/product/index.html.twig', [
            'listProduct' => $this->productRepository->findAll(),
        ]);
    }
}
