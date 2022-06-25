<?php

namespace App\Controller\UserBack;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class RefurbishedToyImageController extends AbstractController
{
    #[Route('/jurassicback/refurbished_toy/image/{fileName}', name: 'user_back.refurbished_toy.image')]
    public function image(string $fileName, KernelInterface $kernel): Response
    {
        $file = new File($kernel->getProjectDir() . '/var/img/' . $fileName);
        return $this->file($file, null, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}