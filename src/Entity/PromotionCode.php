<?php

namespace App\Entity;

use App\Repository\PromotionCodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PromotionCodeRepository::class)
 */
class PromotionCode
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
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="promotionCodes")
     */
    private $customer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $expiresAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $useLimit;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $amountType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stripeId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $couponStripeId;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="promotionCode")
     */
    private $orders;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=true)
     */
    private $minimumAmount;

    /**
     * @ORM\Column(type="boolean")
     */
    private $firstTimeTransaction;

    /**
     * @ORM\Column(type="integer")
     */
    private $useLimitPerCustomer;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
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

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTimeImmutable $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function getUseLimit(): ?int
    {
        return $this->useLimit;
    }

    public function setUseLimit(int $useLimit): self
    {
        $this->useLimit = $useLimit;

        return $this;
    }

    public function getNumberTimeUsed(): int
    {
        $usesList = $this->getOrders()->getValues();
        if (empty($usesList)) {
            return 0;
        }

        $nbUsed = $this->useLimit;
        foreach ($usesList as $use) {
            $nbUsed -= 1;
        }

        return $nbUsed;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmountType(): ?string
    {
        return $this->amountType;
    }

    public function setAmountType(string $amountType): self
    {
        // security to ensure the type of promotion
        if ($amountType !== 'percentage' && $amountType !== 'amount') {
            $amountType = null;
        }

        $this->amountType = $amountType;

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

    public function getCouponStripeId(): ?string
    {
        return $this->couponStripeId;
    }

    public function setCouponStripeId(string $couponStripeId): self
    {
        $this->couponStripeId = $couponStripeId;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        // security to ensure the type of PromotionCode
        if ($type !== 'giftcode' && $type !== 'voucher') {
            $type = null;
        }

        $this->type = $type;

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
            $order->setPromotionCode($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getPromotionCode() === $this) {
                $order->setPromotionCode(null);
            }
        }

        return $this;
    }

    public function getMinimumAmount(): ?string
    {
        return $this->minimumAmount;
    }

    public function setMinimumAmount(?string $minimumAmount): self
    {
        $this->minimumAmount = $minimumAmount;

        return $this;
    }

    public function isFirstTimeTransaction(): ?bool
    {
        return $this->firstTimeTransaction;
    }

    public function setFirstTimeTransaction(bool $firstTimeTransaction): self
    {
        $this->firstTimeTransaction = $firstTimeTransaction;

        return $this;
    }

    public function getUseLimitPerCustomer(): ?int
    {
        return $this->useLimitPerCustomer;
    }

    public function setUseLimitPerCustomer(int $useLimitPerCustomer): self
    {
        $this->useLimitPerCustomer = $useLimitPerCustomer;

        return $this;
    }
}
