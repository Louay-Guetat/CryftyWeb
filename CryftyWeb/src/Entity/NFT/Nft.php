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
     * @Assert\NotNull
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull
     */
    private $price;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Ajouter une image jpg")
     * @Assert\NotNull
     */
    private $image;

    /**
     * @ORM\Column (type="integer")
     */
    private $likes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users\Client", inversedBy="nfts")
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\NFT\Category", inversedBy="nfts")
     * @Assert\NotNull
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\NFT\SubCategory", inversedBy="nfts")
     * @Assert\NotNull
     */
    private $subCategory;

    /**
     * @ORM\OneToMany (targetEntity="App\Entity\NFT\NftComment", mappedBy="nft")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Payment\Cart", mappedBy="nftProd")
     */
    private $cartProd;

    public function getId(): ?int
    {
        return $this->id;
    }





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

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }

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


    /**
     * @return mixed
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param mixed $likes
     */
    public function setLikes($likes): void
    {
        $this->likes = $likes;
    }

}
