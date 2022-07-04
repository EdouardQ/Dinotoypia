<?php

namespace App\Controller\UserBack;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class CustomCrudFieldController extends AbstractController
{
    #[Route('/jurassicback/refurbished_toy/image/{fileName}', name: 'user_back.custom_crud_field.refurbished_toy_image')]
    public function image(string $fileName, KernelInterface $kernel): Response
    {
        $file = new File($kernel->getProjectDir() . '/var/img/' . $fileName);
        return $this->file($file, null, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    #[Route('/jurassicback/product/remove/{id}', name: 'user_back.custom_crud_field.remove_one_product_unit')]
    public function removeOneUnitFromProduct(Product $product, EntityManagerInterface $entityManager, Request $request): Response
    {
        if (!empty($_SERVER['HTTP_REFERER']) && str_contains($_SERVER['HTTP_REFERER'], 'ProductCrudController')) {
            $product->setStock($product->getStock()-1);
            $entityManager->flush();
        }

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}