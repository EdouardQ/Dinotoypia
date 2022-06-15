<?php

namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    private $env;
    private MailerInterface $mailer;
    private bool $prod;

    public function __construct(KernelInterface $kernel, MailerInterface $mailer)
    {
        $this->env = $kernel->getEnvironment();
        $this->mailer = $mailer;
        $this->prod = $this->env === "prod";
    }

    public function test(): void
    {
        if ($this->prod) {
            dd('ok');
        }
        dd('pas ok');
    }
}