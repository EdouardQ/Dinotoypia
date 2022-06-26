<?php

namespace App\Service;

use App\Entity\Order;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

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

    public function sendEmailOrder(Order $order): void
    {
        if($this->prod) {
            $email = (new TemplatedEmail())
                ->from('no-reply@dinotoypia.store')
                ->to($order->getCustomer()->getEmail())
                ->subject('Confirmation de commande')
                ->htmlTemplate('emails/order.html.twig')
                ->context([
                    'order' => $order,
                    'totalPrice' => $order->getTotalPriceOfOrderItems()
                ]);

            $this->mailer->send($email);
        }
    }
}