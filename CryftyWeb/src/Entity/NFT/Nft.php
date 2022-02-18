<?php

namespace App\Entity\NFT;

use App\Repository\NftRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NftRepository::class)
 */
class Nft
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
     * @ORM\OneToMany(targetEntity="App\Entity\Payment\Cart",mappedBy="nftProd")
     */
    private $cartProd;

    /**
     * @return mixed
     */
    public function getCartProd()
    {
        return $this->cartProd;
    }

    /**
     * @param mixed $cartProd
     */
    public function setCartProd($cartProd): void
    {
        $this->cartProd = $cartProd;
    }

}
