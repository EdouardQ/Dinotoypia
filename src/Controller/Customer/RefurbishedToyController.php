<?php

namespace App\Controller\Customer;

use App\Entity\RefurbishedToy;
use App\Entity\RefurbishState;
use App\Form\RefurbishedToyFormType;
use App\Repository\RefurbishedToyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/my-account')]
class RefurbishedToyController extends AbstractController
{
    #[Route('/refurbishment', name: 'customer.refurbished_toy.index')]
    public function index(RefurbishedToyRepository $refurbishedToyRepository): Response
    {
        return $this->render('customer/refurbished_toy/index.html.twig', [
            'refurbishedToyList' => $refurbishedToyRepository->findBy(['customer' => $this->getUser()], ['id' => 'DESC'])
        ]);
    }

    #[Route('/refurbishment/request', name: 'customer.refurbished_toy.request')]
    public function request(EntityManagerInterface $entityManager, Request $request): Response
    {
        $entity = new RefurbishedToy();
        $form = $this->createForm(RefurbishedToyFormType::class, $entity)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity->setCustomer($this->getUser());
            $entity->setState($entityManager->getRepository(RefurbishState::class)->findOneBy(['code' => 'waiting_deposit']));
            $entityManager->persist($entity);
            $entityManager->flush();

            $this->addFlash('refurbishedToyNotice', "Votre demande a été accepté");
            return $this->redirectToRoute('customer.refurbished_toy.index');
        }

        return $this->render('customer/refurbished_toy/request.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
