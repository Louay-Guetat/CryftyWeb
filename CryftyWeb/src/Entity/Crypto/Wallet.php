<?php

namespace App\Entity\Crypto;

use App\Repository\WalletRepository;
use Doctrine\ORM\Mapping as ORM;

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

    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Payment\Cart",mappedBy="wallets")
     */
    private $cartwallet;

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

}
