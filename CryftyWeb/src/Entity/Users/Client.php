<?php

namespace App\Entity\Users;

use App\Entity\Crypto\Wallet;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @UniqueEntity("email")
 */
class Client extends User
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Users\SupportTicket", mappedBy="Client")
     */

    private $supportticket;


    /**
     * @Assert\Length(min=3,max=255)
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @Assert\Length(min=3,max=255)
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Email is required")
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex("/^[0-9]{8}$/")
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *@Assert\Range(min=10,max=90)
     */
    private $age;

    /**
     * @Assert\Length(min=3,max=255)
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;


    /**
     * @ORM\OneToMany (targetEntity="App\Entity\NFT\Nft", mappedBy="owner")
     */
    private $nfts;

    /**
     * @ORM\OneToMany (targetEntity="App\Entity\NFT\NftComment", mappedBy="client")
     */
    private $comments;
    /**
     * @ORM\OneToMany(targetEntity=Wallet::class, mappedBy="client", orphanRemoval=true)
     */
    private $wallets;

    public function __construct()
    {
        $this->wallets = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Payment\Cart",mappedBy="clientId")
     */
    private $cartId;

    /**
     * @return mixed
     */
    public function getCartId()
    {
        return $this->cartId;
    }

    /**
     * @param mixed $cartId
     */
    public function setCartId($cartId): void
    {
        $this->cartId = $cartId;
    }



    /**
     * @return Collection|Wallet[]
     */
    public function getWallets(): Collection
    {
        return $this->wallets;
    }

    public function addWallet(Wallet $wallet): self
    {
        if (!$this->wallets->contains($wallet)) {
            $this->wallets[] = $wallet;
            $wallet->setClient($this);
        }

        return $this;
    }

    public function removeWallet(Wallet $wallet): self
    {
        if ($this->wallets->removeElement($wallet)) {
            // set the owning side to null (unless already changed)
            if ($wallet->getClient() === $this) {
                $wallet->setClient(null);
            }
        }

        return $this;
    }
}
