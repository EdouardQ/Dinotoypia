<?php

namespace App\EventSubscriber;

use App\Entity\GiftCode;
use App\Entity\GiftCodeToCustomer;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\RefurbishedToy;
use App\Entity\UserBack;
use App\Entity\Voucher;
use App\Service\BarCodeService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{

    private UserPasswordHasherInterface $userPasswordHasher;
    private EntityManagerInterface $entityManager;
    private BarCodeService $barCodeService;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, BarCodeService $barCodeService)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->entityManager = $entityManager;
        $this->barCodeService = $barCodeService;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['beforePersist'],
            AfterEntityPersistedEvent::class => ['afterPersist'],
        ];
    }

    public function beforePersist(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof UserBack) {
            $entity->setCreatedBy($this->getUser());
            $entity->setCreatedAt(new \DateTimeImmutable());
            $this->setHashedPassword($entity);
        }
        elseif ($entity instanceof OrderItem) {
            $entity->setPrice($entity->getProduct()->getPrice());
        }
        elseif ($entity instanceof Order) {
            $entity->setCreatedAt(new \DateTimeImmutable());
            $today = new \DateTime();
            $entity->setEstimatedDelivery($today->add(new \DateInterval('P7D')));
        }
        elseif ($entity instanceof RefurbishedToy) {
            $entity->setCreatedAt(new \DateTimeImmutable());
            $entity->setBarCodeNumber('pending');
        }
        elseif ($entity instanceof GiftCode) {
            $entity->setCreatedAt(new \DateTimeImmutable());
        }
        elseif ($entity instanceof GiftCodeToCustomer) {
            $entity->setNumberUsed(1);
        }
        elseif ($entity instanceof Voucher) {
            $entity->setCreatedAt(new \DateTimeImmutable());
            $today = new \DateTime();
            $entity->setExpiresOn($today->add(new \DateInterval('P1Y')));
        }
    }

    public function afterPersist(AfterEntityPersistedEvent $event) {
        $entity = $event->getEntityInstance();
        if ($entity instanceof RefurbishedToy) {
            $entity->setBarCodeNumber($this->barCodeService->generateBarCodeNumber($entity));
            $this->entityManager->flush();
        }
    }

    public function setHashedPassword(UserBack $entity): void
    {
        $password = $entity->getPassword();
        $entity->setPassword($this->userPasswordHasher->hashPassword($entity, $password));
    }
}
