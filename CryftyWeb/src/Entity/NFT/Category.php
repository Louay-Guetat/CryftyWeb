<?php

namespace App\Entity\NFT;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotNull
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


}
