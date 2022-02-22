<?php

namespace App\Entity\Payment;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @param $datetransaction
     */
    public function __construct()
    {
        $this->datetransaction = new \DateTime();
    }

    /**
     * @ORM\Column(type="float")
     */
    private $montant;



    /**
     * @ORM\Column(type="datetime")
     */
    private $datetransaction;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Payment\Cart",inversedBy="cartTransaction")
     */
    private $cartId;


    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Crypto\Wallet",inversedBy="walletTransaction")
     */
    private $wallets;

    /**
     * @return mixed
     */
    public function getDatetransaction()
    {
        return $this->datetransaction;
    }

    /**
     * @param mixed $datetransaction
     */
    public function setDatetransaction($datetransaction): void
    {
        $this->datetransaction = $datetransaction;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getWallets()
    {
        return $this->wallets;
    }

    /**
     * @param mixed $wallets
     */
    public function setWallets($wallets): void
    {
        $this->wallets = $wallets;
    }


    /**
     * @return mixed
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * @param mixed $montant
     */
    public function setMontant($montant): void
    {
        $this->montant = $montant;
    }

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

}
