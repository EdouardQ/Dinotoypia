<?php

namespace App\Entity;

use App\Repository\ToyConditionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ToyConditionRepository::class)
 */
class ToyCondition
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
     * @ORM\OneToMany(targetEntity=RefurbishedToy::class, mappedBy="toyCondition")
     */
    private $refurbishedToys;

    public function __construct()
    {
        $this->refurbishedToys = new ArrayCollection();
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

    /**
     * @return Collection<int, RefurbishedToy>
     */
    public function getRefurbishedToys(): Collection
    {
        return $this->refurbishedToys;
    }

    public function addRefurbishedToy(RefurbishedToy $refurbishedToy): self
    {
        if (!$this->refurbishedToys->contains($refurbishedToy)) {
            $this->refurbishedToys[] = $refurbishedToy;
            $refurbishedToy->setToyCondition($this);
        }

        return $this;
    }

    public function removeRefurbishedToy(RefurbishedToy $refurbishedToy): self
    {
        if ($this->refurbishedToys->removeElement($refurbishedToy)) {
            // set the owning side to null (unless already changed)
            if ($refurbishedToy->getToyCondition() === $this) {
                $refurbishedToy->setToyCondition(null);
            }
        }

        return $this;
    }
}
