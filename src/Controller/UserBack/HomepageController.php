<?php

namespace App\Controller\UserBack;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/jurassicback')]
class HomepageController extends AbstractController
{
    #[Route('/', name: 'user_back.homepage.index')]
    public function index(): Response
    {
        return $this->render('customer/homepage/index.html.twig');
    }
}
