<?php

namespace App\Entity\Payment;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ("wallets:read")
     * @Groups ("cartId:read")
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
     * @ORM\Column(type="datetime")
     * @Groups ("cartId:read")
     * @Groups ("wallets:read")
     */
    private $datetransaction;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Payment\Cart",inversedBy="cartTransaction")
     * @Groups ("cartId:read")
     */
    private $cartId;


    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Crypto\Wallet")
     * @Groups ("wallets:read")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
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
