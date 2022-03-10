<?php

namespace App\Entity\Payment;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ("cartId:read")
     */
    private $id;


    /**
     * @param $date_creation
     */
    public function __construct()
    {
        $this->date_creation = new \DateTime();
        $this->nftProd=new ArrayCollection();
    }

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\NFT\Nft",cascade={"persist"})
     */
    private $nftProd;



    /**
    * @Assert\DateTime
    * @ORM\Column(type="datetime")
    * @var string A "Y-m-d H:i:s" formatted value
     * @Groups ("cartId:read")

     */
    private $date_creation;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Payment\Transaction",mappedBy="cartId")
     */
    private $cartTransaction;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Users\Client",inversedBy="cartId",cascade={"persist", "remove"})
     */
    private $clientId;

    /**
     * @ORM\Column (type="float")
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



    public function getId(): ?int
    {
        return $this->id;
    }






}
