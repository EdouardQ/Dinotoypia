<?php

namespace App\Controller\Customer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer')]
class HomepageController extends AbstractController
{
    #[Route('/', name: 'customer.homepage.index')]
    public function index(): Response
    {
        return $this->render('customer/homepage/index.html.twig');
    }
}
