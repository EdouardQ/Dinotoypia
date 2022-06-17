<?php

namespace App\EventSubscriber;

use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
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
            AfterEntityDeletedEvent::class => ['afterDelete'],
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
        ;
        $log->setAction($user->getId() ." - " . $user . " a ajouté un " . $log->getEntity() .'.');

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
        ;
        $log->setAction($user->getId() ." - " . $user . " a modifié un " . $log->getEntity() .'.');

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    public function afterDelete(AfterEntityDeletedEvent $event): void
    {
        $entity = $event->getEntityInstance();
        $user = $this->security->getUser();

        $log = new Log();
        $log->setUser($user)
            ->setEntity(get_class($entity))
            ->setEntityId($entity->getId())
            ->setLogedAt(new \DateTimeImmutable())
        ;
        $log->setAction($user->getId() ." - " . $user . " a supprimé un " . $log->getEntity() .'.');

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }


}