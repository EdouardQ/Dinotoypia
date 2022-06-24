<?php

namespace App\Controller\Customer;

use App\Entity\RefurbishedToy;
use App\Entity\RefurbishState;
use App\Form\RefurbishedToyFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/my-account')]
class RefurbishedToyController extends AbstractController
{
    #[Route('/refurbishment', name: 'customer.refurbished_toy.form')]
    public function form(EntityManagerInterface $entityManager, Request $request): Response
    {
        $entity = new RefurbishedToy();
        $form = $this->createForm(RefurbishedToyFormType::class, $entity)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity->setCustomer($this->getUser());
            $entity->setState($entityManager->getRepository(RefurbishState::class)->findOneBy(['code' => 'waiting_deposit']));
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->redirectToRoute('customer.refurbished_toy.validation', ['id' => $entity->getId()]);
        }
        
        return $this->render('customer/refurbished_toy/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/refurbishment/validation/{id}', name: 'customer.refurbished_toy.validation')]
    public function validation(RefurbishedToy $refurbishedToy): Response
    {
        dd($refurbishedToy);
    }

}
