<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class Customer implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $zip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="integer")
     */
    private $fidelityPoints;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="customer")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity=Voucher::class, mappedBy="customer")
     */
    private $vouchers;

    /**
     * @ORM\OneToMany(targetEntity=GiftCodeToCustomer::class, mappedBy="customer")
     */
    private $giftCodeToCustomers;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=RefurbishedToy::class, mappedBy="customer")
     */
    private $refurbishedToys;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->vouchers = new ArrayCollection();
        $this->giftCodeToCustomers = new ArrayCollection();
        $this->refurbishedToys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFidelityPoints(): ?int
    {
        return $this->fidelityPoints;
    }

    public function setFidelityPoints(int $fidelityPoints): self
    {
        $this->fidelityPoints = $fidelityPoints;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setCustomer($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCustomer() === $this) {
                $order->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Voucher[]
     */
    public function getVouchers(): Collection
    {
        return $this->vouchers;
    }

    public function addVoucher(Voucher $voucher): self
    {
        if (!$this->vouchers->contains($voucher)) {
            $this->vouchers[] = $voucher;
            $voucher->setCustomer($this);
        }

        return $this;
    }

    public function removeVoucher(Voucher $voucher): self
    {
        if ($this->vouchers->removeElement($voucher)) {
            // set the owning side to null (unless already changed)
            if ($voucher->getCustomer() === $this) {
                $voucher->setCustomer(null);
            }
        }

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
            $giftCodeToCustomer->setCustomer($this);
        }

        return $this;
    }

    public function removeGiftCodeToCustomer(GiftCodeToCustomer $giftCodeToCustomer): self
    {
        if ($this->giftCodeToCustomers->removeElement($giftCodeToCustomer)) {
            // set the owning side to null (unless already changed)
            if ($giftCodeToCustomer->getCustomer() === $this) {
                $giftCodeToCustomer->setCustomer(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

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
            $refurbishedToy->setCustomer($this);
        }

        return $this;
    }

    public function removeRefurbishedToy(RefurbishedToy $refurbishedToy): self
    {
        if ($this->refurbishedToys->removeElement($refurbishedToy)) {
            // set the owning side to null (unless already changed)
            if ($refurbishedToy->getCustomer() === $this) {
                $refurbishedToy->setCustomer(null);
            }
        }

        return $this;
    }
}
