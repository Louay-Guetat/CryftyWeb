<?php

namespace App\Entity\Payment;

use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\NFT\Nft",inversedBy="cartProd")
     */
    private $nftProd;

    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total): void
    {
        $this->total = $total;
    }


    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @return mixed
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * @param mixed $quantite
     */
    public function setQuantite($quantite): void
    {
        $this->quantite = $quantite;
    }


    /**
     * @ORM\Column(type="string")
     */
    private $date_creation;
    
    /**
     * @return mixed
     */
    public function getDateCreation()
    {
        return $this->date_creation;
    }

    /**
     * @param mixed $date_creation
     */
    public function setDateCreation($date_creation): void
    {
        $this->date_creation = $date_creation;
    }
    
    


    /**
     * @return mixed
     */
    public function getNftProd()
    {
        return $this->nftProd;
    }

    /**
     * @param mixed $nftProd
     */
    public function setNftProd($nftProd): void
    {
        $this->nftProd = $nftProd;
    }


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Users\Client",inversedBy="cartId")
     */
    private $clientId;

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Crypto\Wallet",inversedBy="cartwallet")
     */
    private $wallets;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Payment\Transaction",mappedBy="cartId")
     */
    private $cartTransaction;

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
    public function getCartTransaction()
    {
        return $this->cartTransaction;
    }

    /**
     * @param mixed $cartTransaction
     */
    public function setCartTransaction($cartTransaction): void
    {
        $this->cartTransaction = $cartTransaction;
    }







}
