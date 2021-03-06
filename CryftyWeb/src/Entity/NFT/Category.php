<?php

namespace App\Entity\NFT;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Vangrg\ProfanityBundle\Validator\Constraints as ProfanityAssert;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @ProfanityAssert\ProfanityCheck
     * @Assert\NotNull
     * @Groups ("Category:read")
     */
    private $name;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\Column (type="integer")
     */
    private $nbrNft;

    /**
     * @ORM\Column (type="integer")
     */
    private $nbrSubCategory;

    /**
     * @ORM\OneToMany (targetEntity="App\Entity\NFT\Nft", mappedBy="category")
     */
    private $nfts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\NFT\SubCategory", mappedBy="category")
     */
    private $subCategories;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
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
    public function getNfts()
    {
        return $this->nfts;
    }

    /**
     * @param mixed $nfts
     */
    public function setNfts($nfts): void
    {
        $this->nfts = $nfts;
    }

    /**
     * @return mixed
     */
    public function getSubCategories()
    {
        return $this->subCategories;
    }

    /**
     * @param mixed $subCategories
     */
    public function setSubCategories($subCategories): void
    {
        $this->subCategories = $subCategories;
    }

    /**
     * @return mixed
     */
    public function getNbrNft()
    {
        return $this->nbrNft;
    }

    /**
     * @param mixed $nbrNft
     */
    public function setNbrNft($nbrNft): void
    {
        $this->nbrNft = $nbrNft;
    }

    /**
     * @return mixed
     */
    public function getNbrSubCategory()
    {
        return $this->nbrSubCategory;
    }

    /**
     * @param mixed $nbrSubCategory
     */
    public function setNbrSubCategory($nbrSubCategory): void
    {
        $this->nbrSubCategory = $nbrSubCategory;
    }


}
