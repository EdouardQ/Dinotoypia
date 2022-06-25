<?php

namespace App\EventSubscriber;

use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            AfterEntityPersistedEvent::class => ['afterPersist'],
            AfterEntityUpdatedEvent::class => ['afterUpdate'],
            BeforeEntityDeletedEvent::class => ['beforeDelete'],
        ];
    }

    public function afterPersist(AfterEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();
        $user = $this->security->getUser();

        $log = new Log();
        $log->setUser($user)
            ->setEntity(get_class($entity))
            ->setEntityId($entity->getId())
            ->setLogedAt(new \DateTimeImmutable())
            ->setAction('insert')
        ;

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    public function afterUpdate(AfterEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();
        $user = $this->security->getUser();

        $log = new Log();
        $log->setUser($user)
            ->setEntity(get_class($entity))
            ->setEntityId($entity->getId())
            ->setLogedAt(new \DateTimeImmutable())
            ->setAction('update')
        ;

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    public function beforeDelete(BeforeEntityDeletedEvent $event): void
    {
        $entity = $event->getEntityInstance();
        $user = $this->security->getUser();

        $log = new Log();
        $log->setUser($user)
            ->setEntity(get_class($entity))
            ->setEntityId($entity->getId())
            ->setLogedAt(new \DateTimeImmutable())
            ->setAction('delete')
        ;

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }


}