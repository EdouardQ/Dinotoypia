<?php

namespace App\Service;

use App\Entity\RefurbishedToy;
use Symfony\Component\HttpKernel\KernelInterface;

class FileService
{
    private string $fileName;
    private KernelInterface $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function getfileName(): string
    {
        return $this->fileName;
    }

    public function uploadImageFromRefurbishedToyForm(RefurbishedToy $refurbishedToy): void
    {
        // rename
        $name = $refurbishedToy->getBarCodeNumber();
        $extension = $refurbishedToy->getImage()->guessClientExtension(); // image is a UploadedFile

        $this->fileName = "$name.$extension";

        // file transfer
        $refurbishedToy->getImage()->move($this->kernel->getProjectDir() . "/src/RefurbishedToyImage/", $this->fileName);
    }
}
