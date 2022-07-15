<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Service\MailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact.index')]
    public function index(Request $request, MailService $mailService): Response
    {
        $form = $this->createForm(ContactFormType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mailService->sendEmailFromContact($form->getData());
            $this->addFlash('contactNotice', "Demande de contact envoyée avec succès");
            return $this->redirectToRoute('contact.index');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
