<?php

namespace App\Entity;

use App\Repository\RefurbishedToyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=RefurbishedToyRepository::class)
 */
class RefurbishedToy
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="refurbishedToys")
     */
    private $customer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $barCodeNumber;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=RefurbishState::class, inversedBy="refurbishedToys")
     * @ORM\JoinColumn(nullable=false)
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=ToyCondition::class, inversedBy="refurbishedToys")
     * @ORM\JoinColumn(nullable=false)
     */
    private $toyCondition;

    /**
     * @ORM\OneToOne(targetEntity=PromotionCode::class, inversedBy="refurbishedToy", cascade={"remove"})
     */
    private $promotionCode;

    public function __toString(): string
    {
        return $this->barCodeNumber;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBarCodeNumber(): ?string
    {
        return $this->barCodeNumber;
    }

    public function setBarCodeNumber(string $barCodeNumber): self
    {
        $this->barCodeNumber = $barCodeNumber;

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

    public function getState(): ?RefurbishState
    {
        return $this->state;
    }

    public function setState(?RefurbishState $state): self
    {
        $this->state = $state;

        return $this;
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

    public function getImage(): null|string|UploadedFile
    {
        return $this->image;
    }

    public function setImage(null|string|UploadedFile $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getToyCondition(): ?ToyCondition
    {
        return $this->toyCondition;
    }

    public function setToyCondition(?ToyCondition $toyCondition): self
    {
        $this->toyCondition = $toyCondition;

        return $this;
    }

    public function getPromotionCode(): ?PromotionCode
    {
        return $this->promotionCode;
    }

    public function setPromotionCode(?PromotionCode $promotionCode): self
    {
        $this->promotionCode = $promotionCode;

        return $this;
    }
}
