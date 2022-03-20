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
    private $label;

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
    private $expiresOn;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberUsesLimit;

    /**
     * @ORM\OneToMany(targetEntity=GiftCodeToCustomer::class, mappedBy="giftCode")
     */
    private $giftCodeToCustomers;

    public function __construct()
    {
        $this->giftCodeToCustomers = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->label;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

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

    public function getExpiresOn(): ?\DateTimeImmutable
    {
        return $this->expiresOn;
    }

    public function setExpiresOn(\DateTimeImmutable $expiresOn): self
    {
        $this->expiresOn = $expiresOn;

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
}
