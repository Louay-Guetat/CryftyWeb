<?php

namespace App\Entity\NFT;

use App\Repository\SubCategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Vangrg\ProfanityBundle\Validator\Constraints as ProfanityAssert;


/**
 * @ORM\Entity(repositoryClass=SubCategoryRepository::class)
 */
class SubCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ("category1:read")
     * @Groups ("nfts:read")
     */
    private $id;

    /**
     * @ORM\Column (type="string")
     * @ProfanityAssert\ProfanityCheck
     * @Assert\NotNull
     * @Groups ("subCategory:read")
     * @Groups ("category1:read")
     * @Groups ("nfts:read")
     */
    private $name;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     * @Groups ("category1:read")
     * @Groups ("nfts:read")
     */
    private $creationDate;

    /**
     * @ORM\Column (type="integer")
     * @Groups ("category1:read")
     * @Groups ("nfts:read")
     */
    private $nbrNft;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\NFT\Category", inversedBy="subCategories")
     * @Groups ("category1:read")
     */
    private $category;

    /**
     * @ORM\OneToMany (targetEntity="App\Entity\NFT\Nft", mappedBy="subCategory")
     * @Groups ("nfts:read")
     */
    private $nfts;

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


}
