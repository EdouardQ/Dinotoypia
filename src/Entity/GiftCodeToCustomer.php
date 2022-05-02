<?php

namespace App\Entity;

use App\Repository\GiftCodeToCustomerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GiftCodeToCustomerRepository::class)
 */
class GiftCodeToCustomer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="giftCodeToCustomers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity=GiftCode::class, inversedBy="giftCodeToCustomers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $giftCode;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberUsed;

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

    public function getGiftCode(): ?GiftCode
    {
        return $this->giftCode;
    }

    public function setGiftCode(?GiftCode $giftCode): self
    {
        $this->giftCode = $giftCode;

        return $this;
    }

    public function getNumberUsed(): ?int
    {
        return $this->numberUsed;
    }

    public function setNumberUsed(int $numberUsed): self
    {
        $this->numberUsed = $numberUsed;

        return $this;
    }
}
