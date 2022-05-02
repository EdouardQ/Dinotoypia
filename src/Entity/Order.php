<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=State::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $state;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trackingNumber;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $estimatedDelivery;

    /**
     * @ORM\OneToMany(targetEntity=OrderItem::class, mappedBy="order", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $orderItems;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="orders")
     */
    private $customer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $relay_point_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paymentStripeId;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTrackingNumber(): ?string
    {
        return $this->trackingNumber;
    }

    public function setTrackingNumber(string $trackingNumber): self
    {
        $this->trackingNumber = $trackingNumber;

        return $this;
    }

    public function getEstimatedDelivery(): ?\DateTime
    {
        return $this->estimatedDelivery;
    }

    public function setEstimatedDelivery(?\DateTime $estimatedDelivery): self
    {
        $this->estimatedDelivery = $estimatedDelivery;

        return $this;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems[] = $orderItem;
            $orderItem->setOrder($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getOrder() === $this) {
                $orderItem->setOrder(null);
            }
        }

        return $this;
    }

    public function getTotalPriceOfOrderItems(): float
    {
        $orderItems = $this->getOrderItems()->getValues();
        if (empty($orderItems)) {
            return 0;
        }
        $total = 0;
        foreach ($orderItems as $item) {
            $total+=$item->getPrice()*$item->getQuantity();
        }
        return $total;
    }

    public function getTotalQuantity(): int
    {
        $orderItems = $this->getOrderItems()->getValues();
        if (empty($orderItems)) {
            return 0;
        }
        $total = 0;
        foreach ($orderItems as $item) {
            $total+=$item->getQuantity();
        }
        return $total;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getRelayPointId(): ?string
    {
        return $this->relay_point_id;
    }

    public function setRelayPointId(?string $relay_point_id): self
    {
        $this->relay_point_id = $relay_point_id;

        return $this;
    }

    public function getPaymentStripeId(): ?string
    {
        return $this->paymentStripeId;
    }

    public function setPaymentStripeId(?string $paymentStripeId): self
    {
        $this->paymentStripeId = $paymentStripeId;

        return $this;
    }

    public function getStripeLineItems(): ?array
    {
        $array = [];

        foreach ($this->getOrderItems()->getValues() as $item) {
            $array[] = [
                'price' => $item->getProduct()->getPriceStripeId(),
                'quantity' => $item->getQuantity()
            ];
        }

        return $array;
    }

    public function calculEstimatedDeliveryDateTime(int $days = 5): \DateTime
    {
        $today = new \DateTime();
        return $today->add(new \DateInterval('P'.$days.'D'));
    }
}
