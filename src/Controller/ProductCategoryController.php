<?php

namespace App\Controller;

use App\Entity\ProductCategory;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category')]
class ProductCategoryController extends AbstractController
{
    #[Route('/{id}',  name: 'category.index')]
    public function index(ProductCategory $productCategory, ProductRepository $productRepository): Response
    {
        $productList = $productRepository->findByCatogory($productCategory);
        return $this->render('category/index.html.twig', [
            'productList' => $productList
        ]);
    }
}