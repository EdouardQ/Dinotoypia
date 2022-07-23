<?php

namespace App\Entity;

use App\Repository\ShippingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ShippingRepository::class)
 */
class Shipping
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stripeId;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $fee;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="shipping")
     */
    private $orders;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="integer")
     * @Assert\LessThanOrEqual(propertyPath="deliveryEstimateMaximum")
     */
    public $deliveryEstimateMinimum;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(propertyPath="deliveryEstimateMinimum")
     */
    public $deliveryEstimateMaximum;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name . " (" . $this->deliveryEstimateMinimum . " - " . $this->deliveryEstimateMaximum . " jours)";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStripeId(): ?string
    {
        return $this->stripeId;
    }

    public function setStripeId(string $stripeId): self
    {
        $this->stripeId = $stripeId;

        return $this;
    }

    public function getFee(): ?string
    {
        return $this->fee;
    }

    public function setFee(string $fee): self
    {
        $this->fee = $fee;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setShipping($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getShipping() === $this) {
                $order->setShipping(null);
            }
        }

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getDeliveryEstimateMinimum(): ?int
    {
        return $this->deliveryEstimateMinimum;
    }

    public function setDeliveryEstimateMinimum(int $deliveryEstimateMinimum): self
    {
        $this->deliveryEstimateMinimum = $deliveryEstimateMinimum;

        return $this;
    }

    public function getDeliveryEstimateMaximum(): ?int
    {
        return $this->deliveryEstimateMaximum;
    }

    public function setDeliveryEstimateMaximum(int $deliveryEstimateMaximum): self
    {
        $this->deliveryEstimateMaximum = $deliveryEstimateMaximum;

        return $this;
    }

    public function isDateValid(): bool
    {
        return ($this->deliveryEstimateMinimum >= $this->deliveryEstimateMaximum);
    }
}
