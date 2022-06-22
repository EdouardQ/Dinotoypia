<?php

namespace App\Command;

use App\Entity\BillingAddress;
use App\Entity\DeliveryAddress;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:remove-unused-addresses',
    description: 'Remove unused DeliveryAddress and BillingAddress',
)]
class RemoveUnusedAddresses extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $removeUnusedAddresses = 0;

        foreach ($this->entityManager->getRepository(DeliveryAddress::class)->findUnusedDeliveryAddress() as $deliveryAddress) {
            $this->entityManager->remove($deliveryAddress);
            $removeUnusedAddresses += 1;
        }

        foreach ($this->entityManager->getRepository(BillingAddress::class)->findUnusedBillingAddress() as $billingAddress) {
            $this->entityManager->remove($billingAddress);
            $removeUnusedAddresses += 1;
        }

        $this->entityManager->flush(); // Executes all deletions
        $this->entityManager->clear(); // Detaches all object from Doctrine

        if ($removeUnusedAddresses) {
            $io->success("$removeUnusedAddresses address(es) have been deleted.");
        } else {
            $io->info('No unused address.');
        }

        return Command::SUCCESS;
    }
}
