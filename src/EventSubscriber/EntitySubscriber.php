<?php

namespace App\EventSubscriber;

use App\Entity\Customer;
use App\Entity\Image;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\PromotionCode;
use App\Entity\RefurbishedToy;
use App\Entity\UserBack;
use App\Service\BarCodeService;
use App\Service\StripeService;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

class EntitySubscriber implements EventSubscriberInterface
{
    private BarCodeService $barCodeService;
    private Security $security;
    private StripeService $stripeService;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(BarCodeService $barCodeService, Security $security, StripeService $stripeService, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->barCodeService = $barCodeService;
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
                $entity->setCreatedAt(new \DateTimeImmutable());
                $today = new \DateTime();
                $entity->setEstimatedDelivery($today->add(new \DateInterval('P7D')));
            }
            elseif ($entity instanceof RefurbishedToy) {
                $entity->setCreatedAt(new \DateTimeImmutable());
                $entity->setBarCodeNumber('pending');
            }
            elseif ($entity instanceof PromotionCode) {
                $entity->setCreatedAt(new \DateTimeImmutable());
            }
            elseif ($entity instanceof Image) {
                $this->stripeService->updateImageToStripeProduct($entity);
            }
        }
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof RefurbishedToy) {
            $entity->setBarCodeNumber($this->barCodeService->generateBarCodeNumber($entity));
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
    }
}
