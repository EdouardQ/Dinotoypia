<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'homepage.index')]
    public function index(): Response
    {
        return $this->render('homepage/index.html.twig');
        
    }

    #[Route('/search/{requestString}', name: 'homepage.search')]
    public function search(string $requestString, Request $request, EntityManagerInterface $entityManager): Response
    {
        //$requestString = $request->get('search');
        $products = $entityManager->getRepository(Product::class)->findProductsByString($requestString);

        if (!$products) {
            $result['error'] = "Aucun résultat";
        }
        else {
            foreach ($products as $product) {
                $result[$product->getId()] = [
                    'name' => $product->getName(),
                    'urlName' => $product->getUrlName(),
                    'image' => $product->getImages()->getValues()[0]->getFileName(), // index 0 to get the first image
                ];
            }
        }
        return new Response(json_encode($result));
    }
}
