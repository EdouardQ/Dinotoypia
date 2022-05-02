<?php

namespace App\Entity;

use App\Repository\GiftCodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GiftCodeRepository::class)
 */
class GiftCode
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
    private $code;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="date_immutable")
     */
    private $expiresAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberUsesLimit;

    /**
     * @ORM\OneToMany(targetEntity=GiftCodeToCustomer::class, mappedBy="giftCode")
     */
    private $giftCodeToCustomers;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $giftCodeStripeId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $couponStripeId;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    public function __construct()
    {
        $this->giftCodeToCustomers = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
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

    public function setExpiresAt(\DateTimeImmutable $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function getNumberUsesLimit(): ?int
    {
        return $this->numberUsesLimit;
    }

    public function setNumberUsesLimit(int $numberUsesLimit): self
    {
        $this->numberUsesLimit = $numberUsesLimit;

        return $this;
    }

    /**
     * @return Collection|GiftCodeToCustomer[]
     */
    public function getGiftCodeToCustomers(): Collection
    {
        return $this->giftCodeToCustomers;
    }

    public function addGiftCodeToCustomer(GiftCodeToCustomer $giftCodeToCustomer): self
    {
        if (!$this->giftCodeToCustomers->contains($giftCodeToCustomer)) {
            $this->giftCodeToCustomers[] = $giftCodeToCustomer;
            $giftCodeToCustomer->setGiftCode($this);
        }

        return $this;
    }

    public function removeGiftCodeToCustomer(GiftCodeToCustomer $giftCodeToCustomer): self
    {
        if ($this->giftCodeToCustomers->removeElement($giftCodeToCustomer)) {
            // set the owning side to null (unless already changed)
            if ($giftCodeToCustomer->getGiftCode() === $this) {
                $giftCodeToCustomer->setGiftCode(null);
            }
        }

        return $this;
    }

    public function getNumberRemainingUses(): int
    {
        $usesList = $this->giftCodeToCustomers->getValues();
        if (empty($usesList)) {
            return 0;
        }
        $remainingUses = $this->numberUsesLimit;
        foreach ($usesList as $use) {
            $remainingUses-=$use->getNumberUsed();
        }
        return $remainingUses;
    }

    public function getGiftCodeStripeId(): ?string
    {
        return $this->getGiftCodeStripeId();
    }

    public function setGiftCodeStripeId(string $giftCodeStripeId): self
    {
        $this->giftCodeStripeId = $giftCodeStripeId;

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

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
