<?php

namespace App\Entity\Payment;

use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToOne(targetEntity="App\entity\NFT\Nft",inversedBy="cartProd")
     */
    private $nftProd;

    public function getId(): ?int
    {
        return $this->id;
    }
}
