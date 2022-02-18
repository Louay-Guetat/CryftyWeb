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


    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\Column(type="string")
     */
    private $client;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Email is required")
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     */
    private $email;


    /**
     * ORM\DateTime()
     * ORM\Column(type="datetime")
     */
    private $date_transaction;



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
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     */
    public function setClient($client): void
    {
        $this->client = $client;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }



    /**
     * @return mixed
     */
    public function getDateTransaction()
    {
        return $this->date_transaction;
    }

    /**
     * @param mixed $date_transaction
     */
    public function setDateTransaction($date_transaction): void
    {
        $this->date_transaction = $date_transaction;
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
