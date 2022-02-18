<?php

namespace App\Entity\Crypto;

use App\Repository\TransferRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransferRepository::class)
 */
class Transfer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity=Wallet::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $senderId;

    /**
     * @ORM\ManyToOne(targetEntity=Wallet::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $recieverId;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getSenderId(): ?Wallet
    {
        return $this->senderId;
    }

    public function setSenderId(Wallet $senderId): self
    {
        $this->senderId = $senderId;

        return $this;
    }

    public function getRecieverId(): ?Wallet
    {
        return $this->recieverId;
    }

    public function setRecieverId(Wallet $recieverId): self
    {
        $this->recieverId = $recieverId;

        return $this;
    }

}
