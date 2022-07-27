<?php

namespace App\Controller;

use App\Entity\ProductCategory;
use App\Repository\ProductRepository;
use App\Repository\ProductCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category')]
class ProductCategoryController extends AbstractController
{
    #[Route('/',  name: 'category.index')]
    public function index(ProductCategoryRepository $productCategoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categoryList' => $productCategoryRepository->findAll()
        ]);
    }

    #[Route('/{id}',  name: 'category.cat')]
    public function productCategory(ProductCategory $productCategory, ProductRepository $productRepository): Response
    {
        $productList = $productRepository->findByCatogory($productCategory);
        return $this->render('category/category_products.html.twig', [
            'productList' => $productList,
            'nameCategory' => $productCategory->getName()
        ]);
    }
}