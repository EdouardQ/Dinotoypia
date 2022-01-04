<?php

namespace App\Controller\UserBack;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/', name: 'user_back.product.index')]
    public function index(): Response
    {
        return $this->render('user_back/product/index.html.twig', [
            'listProduct' => $this->productRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'user_back.product.create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $entity = new Product();
        $form = $this->createForm(ProductFormType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($entity);
            $entityManager->flush($entity);

            return $this->redirectToRoute('user_back.product.index');
        }

        return $this->render('user_back/product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/update/{id}', name: 'user_back.product.update')]
    public function update(Product $entity, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(ProductFormType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush($entity);

            return $this->redirectToRoute('user_back.product.index');
        }

        return $this->render('user_back/product/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
