<?php

namespace App\Entity;

use App\Repository\LogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LogRepository::class)
 */
class Log
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=UserBack::class, inversedBy="logs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $entity;

    /**
     * @ORM\Column(type="integer")
     */
    private $entityId;

    /**
     * @ORM\Column(type="text")
     */
    private $action;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $logedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?UserBack
    {
        return $this->user;
    }

    public function setUser(?UserBack $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEntity(): ?string
    {
        return $this->entity;
    }

    public function setEntity(string $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    public function setEntityId(int $entityId): self
    {
        $this->entityId = $entityId;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getLogedAt(): ?\DateTimeImmutable
    {
        return $this->logedAt;
    }

    public function setLogedAt(\DateTimeImmutable $logedAt): self
    {
        $this->logedAt = $logedAt;

        return $this;
    }
}
