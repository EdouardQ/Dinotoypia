<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\RefurbishedToy;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class MailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmailOrder(Order $order): void
    {
        $email = (new TemplatedEmail())
            ->from('no-reply@dinotoypia.store')
            ->to($order->getCustomer()->getEmail())
            ->subject('Confirmation de commande')
            ->htmlTemplate('emails/order.html.twig')
            ->context([
                'order' => $order,
            ]);

        $this->mailer->send($email);
    }

    public function sendEmailFromContact(array $contactForm): void
    {
        $email = (new TemplatedEmail())
            ->from('no-reply@dinotoypia.store')
            ->to('no-reply@dinotoypia.store')
            ->subject('Contact de ' . $contactForm['email'])
            ->htmlTemplate('emails/contact.html.twig')
            ->context([
                'contactForm' => $contactForm,
            ]);

        $this->mailer->send($email);
    }

    public function sendEmailAcceptedRefurbishedToy(RefurbishedToy $refurbishedToy): void
    {
        $email = (new TemplatedEmail())
            ->from('no-reply@dinotoypia.store')
            ->to($refurbishedToy->getCustomer()->getEmail())
            ->subject('Demande de reconditionnement')
            ->htmlTemplate('emails/accepted_refurbished_toy.html.twig')
            ->context([
                'refurbishedToy' => $refurbishedToy,
            ]);

        $this->mailer->send($email);
    }

    public function sendEmailRefusedRefurbishedToy(RefurbishedToy $refurbishedToy): void
    {
        $email = (new TemplatedEmail())
            ->from('no-reply@dinotoypia.store')
            ->to($refurbishedToy->getCustomer()->getEmail())
            ->subject('Demande de reconditionnement')
            ->htmlTemplate('emails/refused_refurbished_toy.html.twig')
            ->context([
                'refurbishedToy' => $refurbishedToy,
            ]);

        $this->mailer->send($email);
    }
}
