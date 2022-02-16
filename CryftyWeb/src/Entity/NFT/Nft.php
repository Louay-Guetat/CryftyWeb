<?php

namespace App\Entity\NFT;

use App\Repository\NftRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users\Client", inversedBy="nfts")
     */
    private $owner;

    /**
     * @ORM\Column ManyToOne(targetEntity="App\Entity\NFT\Category", inversedBy="nfts")
     */
    private $category;

    /**
     * @ORM\Column ManyToOne(targetEntity="App\Entity\NFT\SubCategory", inversedBy="nfts")
     */
    private $subCategory;

    /**
     * @ORM\OneToMany (targetEntity="App\Entity\NFT\NftComment", mappedBy="nft")
     */
    private $comments;

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @ORM\OneToMany(targetEntity="App\entity\Payment\Cart",mappedBy="cartProd")
     */
    private $nftProd;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param mixed $creationDate
     */
    public function setCreationDate($creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getSubCategory()
    {
        return $this->subCategory;
    }

    /**
     * @param mixed $subCategory
     */
    public function setSubCategory($subCategory): void
    {
        $this->subCategory = $subCategory;
    }

    /**
     * @return array
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    /**
     * @param array $comments
     */
    public function setComments(array $comments): void
    {
        $this->comments = $comments;
    }

}
