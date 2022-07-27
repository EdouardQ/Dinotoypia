<?php

namespace App\Controller;

use App\Entity\Product;
use App\Manager\OrderManager;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'homepage.index')]
    public function index(ProductRepository $productRepository): Response
    {
        $listFigurines = $productRepository->findByCatogoryAndByReleaseDate('figurines');
        $listGames = $productRepository->findByCatogoryAndByReleaseDate('jeux de société et puzzles');
        return $this->render('homepage/index.html.twig', [
            'listFigurines' => $listFigurines,
            'listGames' => $listGames,
        ]);
    }

    #[Route('/search/{requestString}', name: 'homepage.search', defaults: ['requestString' => null])]
    public function search(?string $requestString, Request $request, EntityManagerInterface $entityManager): Response
    {
        // if http method is GET with a parameter (jquery)
        if ($request->getMethod() == "GET" && $requestString != null) {
            $products = $entityManager->getRepository(Product::class)->findProductsByString($requestString, 10);

            if (!$products) {
                $result['error'] = "Aucun résultat";
            }
            else {
                foreach ($products as $product) {
                    $result[$product->getId()] = [
                        'name' => $product->getName(),
                        'urlName' => $product->getUrlName(),
                        'image' => $product->getImages()->getValues()[0]->getFileName(), // index 0 to get the first image
                        'price' => $product->getPrice(),
                    ];
                }
            }
            return new Response(json_encode($result));
        }
        // if http method is POST
        elseif ($request->getMethod() == "POST") {
            $requestString = $request->request->get('search');
            $products = $entityManager->getRepository(Product::class)->findProductsByString($requestString);

            return $this->render('homepage/search.html.twig', [
                'products' => $products,
            ]);
        }
        // if user goes to this url by the wrong way -> redirect to the homepage
        return $this->redirectToRoute('homepage.index');
    }

    #[Route('/summary', name: 'homepage.summary')]
    public function summary(OrderManager $orderManager): Response
    {
        return $this->render('homepage/summary.html.twig', [
            'order' => $orderManager->createCheckout(),
        ]);
    }

    #[Route('/refurbished_toy', name: 'homepage.refurbished_toy')]
    public function refurbished_toy(): Response
    {
        return $this->render('homepage/refurbished_toy.html.twig');
    }
}
