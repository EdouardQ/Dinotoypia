<?php

namespace App\Entity;

use App\Repository\RefurbishStateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RefurbishStateRepository::class)
 */
class RefurbishState
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
     * @ORM\OneToMany(targetEntity=RefurbishedToy::class, mappedBy="state")
     */
    private $refurbishedToys;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    public function __construct()
    {
        $this->refurbishedToys = new ArrayCollection();
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

    /**
     * @return Collection|RefurbishedToy[]
     */
    public function getRefurbishedToys(): Collection
    {
        return $this->refurbishedToys;
    }

    public function addRefurbishedToy(RefurbishedToy $refurbishedToy): self
    {
        if (!$this->refurbishedToys->contains($refurbishedToy)) {
            $this->refurbishedToys[] = $refurbishedToy;
            $refurbishedToy->setState($this);
        }

        return $this;
    }

    public function removeRefurbishedToy(RefurbishedToy $refurbishedToy): self
    {
        if ($this->refurbishedToys->removeElement($refurbishedToy)) {
            // set the owning side to null (unless already changed)
            if ($refurbishedToy->getState() === $this) {
                $refurbishedToy->setState(null);
            }
        }

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
}
