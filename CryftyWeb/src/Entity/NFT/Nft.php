<?php

namespace App\Entity\NFT;

use App\Repository\NftRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Vangrg\ProfanityBundle\Validator\Constraints as ProfanityAssert;
/**
 * @ORM\Entity(repositoryClass=NftRepository::class)
 */
class Nft
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ("Category:read")
     * @Groups ("subCategory:read")
     * @Groups ("currency:read")
     * @Groups ("comments:read")
     * @Groups ("CartProd:read")
     * @Groups ("owner:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @ProfanityAssert\ProfanityCheck
     * @Groups ("Category:read")
     * @Groups ("subCategory:read")
     * @Groups ("currency:read")
     * @Groups ("comments:read")
     * @Groups ("CartProd:read")
     * @Groups ("owner:read")
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     * @ProfanityAssert\ProfanityCheck
     * @Groups ("Category:read")
     * @Groups ("subCategory:read")
     * @Groups ("currency:read")
     * @Groups ("comments:read")
     * @Groups ("CartProd:read")
     * @Groups ("owner:read")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Groups ("Category:read")
     * @Groups ("subCategory:read")
     * @Groups ("currency:read")
     * @Groups ("comments:read")
     * @Groups ("CartProd:read")
     * @Groups ("owner:read")
     */
    private $price;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Crypto\Node", inversedBy="coinCode")
     * @Groups ("currency:read")
     */
    private $currency;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     * @Groups ("Category:read")
     * @Groups ("subCategory:read")
     * @Groups ("currency:read")
     * @Groups ("comments:read")
     * @Groups ("CartProd:read")
     * @Groups ("owner:read")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Ajouter un média")
     * @Assert\Image(
     *     mimeTypes="image/*")
     * @Groups ("Category:read")
     * @Groups ("subCategory:read")
     * @Groups ("currency:read")
     * @Groups ("comments:read")
     * @Groups ("CartProd:read")
     * @Groups ("owner:read")
     */
    private $image;

    /**
     * @ORM\Column (type="integer")
     * @Groups ("Category:read")
     * @Groups ("subCategory:read")
     * @Groups ("currency:read")
     * @Groups ("comments:read")
     * @Groups ("CartProd:read")
     * @Groups ("owner:read")
     */
    private $likes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users\Client", inversedBy="nfts")
     * @Groups ("owner:read")
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\NFT\Category", inversedBy="nfts")
     * @Groups ("Category:read")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\NFT\SubCategory", inversedBy="nfts")
     * @Groups ("subCategory:read")
     */
    private $subCategory;

    /**
     * @ORM\OneToMany (targetEntity="App\Entity\NFT\NftComment", mappedBy="nft",orphanRemoval=true)
     * @Groups ("comments:read")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Payment\Cart",cascade={"persist"})
     */
    private $cartProd;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Users\Client")
     *
     */
    private $likedBy;

    /**
     * @param $cartProd
     */
    public function __construct()
    {
        $this->cartProd =new ArrayCollection();
        $this->likedBy = new ArrayCollection();
    }


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
    public function setCartProd($cartProd)
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

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments): void
    {
        $this->comments = $comments;
    }

    /**
     * @return mixed
     */
    public function getLikedBy()
    {
        return $this->likedBy;
    }

    /**
     * @param mixed $likedBy
     */
    public function setLikedBy($client): void
    {
        $this->likedBy[count($this->likedBy)] = $client;
    }

    public function removeLikedBy($client): void
    {
        for($i=0;$i<count($this->likedBy);$i++){
            if($client == $this->likedBy[$i]){
                unset($this->likedBy[$i]);
            }
        }
    }




}
