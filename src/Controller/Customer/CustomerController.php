<?php

namespace App\Controller\Customer;

use App\Form\ChangePasswordFormType;
use App\Form\CustomerFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/my-account')]
class CustomerController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/infos', name: 'customer.customer.index')]
    public function index(): Response
    {
        return $this->render('customer/customer/index.html.twig');
    }

    #[Route('/infos/modify', name: 'customer.customer.form')]
    public function form(EntityManagerInterface $entityManager, Request $request): Response
    {
        $customer = $this->getUser();
        $customer->previousEmail = $customer->getEmail();
        $form = $this->createForm(CustomerFormType::class, $customer)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($customer->getEmail() !== $customer->previousEmail) {
                $this->emailVerifier->sendEmailConfirmation('registration.verify_email', $customer,
                    (new TemplatedEmail())
                        ->from(new Address('no-reply@dinotoypia.store', 'Dinotoypia.store'))
                        ->to($customer->getEmail())
                        ->subject("Confirmation d'adresse email")
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                        ->context([
                            'customer' => $customer
                        ])
                );
                $this->addFlash('customerEmailNotice', "Un email de confirmation a été envoyé");
            }
            $this->addFlash('customerNotice', "Vos informations ont été mises à jour");
            $entityManager->flush();

            return $this->redirectToRoute('customer.customer.form');
        }

        return $this->render('customer/customer/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/infos/password', name: 'customer.customer.form_password')]
    public function formPassword(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $customer = $this->getUser();

        $form = $this->createForm(ChangePasswordFormType::class, $customer)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer->setPassword(
                $userPasswordHasher->hashPassword(
                    $customer,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->addFlash('customerNotice', "Vos informations ont été mises à jour");
            $entityManager->flush();
            return $this->redirectToRoute('customer.customer.form_password');
        }


        return $this->render('customer/customer/form_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
