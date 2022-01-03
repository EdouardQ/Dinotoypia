<?php

namespace App\Controller\UserBack;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('jurassicback/product')]
class ProductController extends AbstractController
{
    private ProductRepository $productRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ProductRepository $productRepository, EntityManagerInterface $entityManager)
    {
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'user_back.product.index')]
    public function index(): Response
    {
        return $this->render('user_back/product/index.html.twig', [
            'listProduct' => $this->productRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'user_back.product.create')]
    public function create(Request $request): Response
    {
        $entity = new Product();
        $form = $this->createForm(ProductFormType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush($entity);

            return $this->redirectToRoute('user_back.product.index');
        }

        return $this->render('user_back/product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/update/{id}', name: 'user_back.product.update')]
    public function update(Product $entity, Request $request): Response
    {
        $form = $this->createForm(ProductFormType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush($entity);

            return $this->redirectToRoute('user_back.product.index');
        }

        return $this->render('user_back/product/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
