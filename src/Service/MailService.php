<?php

namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;

class MailService
{
    private $env;
    private bool $prod;

    public function __construct(KernelInterface $kernel)
    {
        $this->env = $kernel->getEnvironment();
        $this->prod = $this->env === "dev";
    }

    public function test(): void
    {
        if ($this->prod) {
            dd('ok');
        }
        dd('pas ok');
    }
}