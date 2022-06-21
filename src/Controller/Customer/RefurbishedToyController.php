<?php

namespace App\Controller\Customer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/my-account')]
class RefurbishedToyController extends AbstractController
{
    #[Route('/refurbishment', name: 'customer.refurbished_toy.index')]
    public function index(): Response
    {
        return $this->render('customer/refurbished_toy/index.html.twig');
    }

}
