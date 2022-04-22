<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category')]
class ProductCategory extends AbstractController
{
    #[Route('/{id}')]
    public function index(): Response
    {
        return $this->render('');
    }
}