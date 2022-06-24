<?php

namespace App\EventSubscriber;

use App\Entity\Customer;
use App\Entity\Image;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\PromotionCode;
use App\Entity\RefurbishedToy;
use App\Entity\Shipping;
use App\Entity\State;
use App\Entity\UserBack;
use App\Service\FileService;
use App\Service\StripeService;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

class EntitySubscriber implements EventSubscriberInterface
{
    private FileService $fileService;
    private Security $security;
    private StripeService $stripeService;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(FileService $fileService, Security $security, StripeService $stripeService, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->fileService = $fileService;
        $this->security = $security;
        $this->stripeService = $stripeService;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::postPersist,
            Events::preUpdate,
            Events::preRemove,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        if ('cli' != php_sapi_name()) {
            $entity = $args->getObject();

            if ($entity instanceof Product) {
                $this->stripeService->createProduct($entity);
            }
            elseif ($entity instanceof UserBack) {
                $entity->setCreatedBy($this->security->getUser());
                $entity->setCreatedAt(new \DateTimeImmutable());
                $entity->setPassword($this->userPasswordHasher->hashPassword($entity, $entity->getPassword()));
            }
            elseif ($entity instanceof Customer) {
                $this->stripeService->createCustomer($entity);
            }
            elseif ($entity instanceof OrderItem) {
                $entity->setPrice($entity->getProduct()->getPrice());
            }
            elseif ($entity instanceof Order) {
                $now = new \DateTimeImmutable();
                $entity->setCreatedAt($now)
                    ->setUpdatedAt($now)
                    ->setState($args->getObjectManager()
                        ->getRepository(State::class)
                        ->findOneBy(['code' => "pending"]))
                ;
            }
            elseif ($entity instanceof RefurbishedToy) {
                $entity->setCreatedAt(new \DateTimeImmutable());
                $entity->setBarCodeNumber('pending');
            }
            elseif ($entity instanceof PromotionCode) {
                $entity->setCreatedAt(new \DateTimeImmutable());
                $this->stripeService->createPromotionCode($entity);
            }
            elseif ($entity instanceof Image) {
                $this->stripeService->updateImageToStripeProduct($entity);
            }
            elseif ($entity instanceof Shipping) {
                $entity->setActive(true);
                $this->stripeService->createShipping($entity);
            }
        }
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof RefurbishedToy) {
            $entity->setBarCodeNumber('dino-'.$entity->getId().'-'.time());
            $this->fileService->uploadImageFromRefurbishedToyForm($entity);
            $entity->setImage($this->fileService->getfileName());
            $args->getObjectManager()->flush();
        }
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof Product) {
            if ($args->hasChangedField('price')) {
                $priceAlreadyExists = false;
                $otherExistingPriceList = $this->stripeService->findAllPriceFromProduct($entity->getProductStripeId());

                foreach ($otherExistingPriceList as $price) {
                    if (($price->unit_amount / 100) == $args->getNewValue('price')) {
                        $entity->setPriceStripeId($price->id);
                        $priceAlreadyExists = true;
                    }
                }

                if (!$priceAlreadyExists) {
                    $this->stripeService->createPrice($entity);
                }
            }
            if ($args->hasChangedField('name') || $args->hasChangedField('description')) {
                $this->stripeService->updateProduct($entity);
            }
        }
        elseif ($entity instanceof Image) {
            $this->stripeService->updateImageToStripeProduct($entity);
        }

        elseif ($entity instanceof Order) {
            $entity->setUpdatedAt(new \DateTimeImmutable());
        }
        elseif ($entity instanceof Shipping && $args->hasChangedField('active')) {
            $this->stripeService->updateShipping($entity);
        }
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof PromotionCode) {
            $this->stripeService->deletePromotionCode($entity);
        }
    }
}
