<?php

namespace App\Entity\Crypto;

use App\Entity\Users\Client;
use App\Repository\WalletRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=WalletRepository::class)
 */
class Wallet
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
    private $walletAddress;

    /**
     * @ORM\Column(type="float")
     */
    private $balance;

    /**
     * @ORM\ManyToOne (targetEntity=Node::class)
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @Assert\NotBlank
     */
    private $NodeId;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(min=5,max=50,
     *                minMessage="Your label should longer than {{ limit }} characters ",
     *                maxMessage="Your address should be less than {{ limit }} characters")
     */
    private $walletLabel;

    /**
     * @ORM\OneToMany(targetEntity=Block::class, mappedBy="wallet")
     */
    private $block;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="wallets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Payment\Cart",mappedBy="wallets")
     */
    private $cartwallet;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $walletImageFileName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @return mixed
     */
    public function getCartwallet()
    {
        return $this->cartwallet;
    }

    /**
     * @param mixed $cartwallet
     */
    public function setCartwallet($cartwallet): void
    {
        $this->cartwallet = $cartwallet;
    }


    public function getWalletAddress(): ?string
    {
        return $this->walletAddress;
    }

    public function setWalletAddress(string $walletAddress): self
    {
        $this->walletAddress = $walletAddress;

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getNodeId(): ?Node
    {
        return $this->NodeId;
    }

    public function setNodeId(Node $NodeId): self
    {
        $this->NodeId = $NodeId;

        return $this;
    }

    public function getWalletLabel(): ?string
    {
        return $this->walletLabel;
    }

    public function setWalletLabel(string $walletLabel): self
    {
        $this->walletLabel = $walletLabel;

        return $this;
    }

    public function getBlock(): ?Block
    {
        return $this->block;
    }

    public function setBlock(Block $block): self
    {
        // set the owning side of the relation if necessary
        if ($block->getWallet() !== $this) {
            $block->setWallet($this);
        }

        $this->block = $block;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getWalletImageFileName(): ?string
    {
        return $this->walletImageFileName;
    }

    public function setWalletImageFileName(?string $walletImageFileName): self
    {
        $this->walletImageFileName = $walletImageFileName;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

}
