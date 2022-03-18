<?php

namespace App\EventSubscriber;

use App\Entity\Order;
use App\Entity\UserBack;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['beforePersist'],
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
        elseif ($entity instanceof Order) {
            $entity->setCreatedAt(new \DateTimeImmutable());
            $today = new \DateTime();
            $entity->setEstimatedDelivery($today->add(new \DateInterval('P7D')));
        }
    }

    public function setHashedPassword(UserBack $entity): void
    {
        $password = $entity->getPassword();
        $entity->setPassword($this->userPasswordHasher->hashPassword($entity, $password));
    }
}
