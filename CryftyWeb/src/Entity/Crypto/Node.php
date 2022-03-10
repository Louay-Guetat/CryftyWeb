<?php

namespace App\Entity\Crypto;

use App\Repository\NodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=NodeRepository::class)
 */
class Node
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(min=5,max=20,
     *     minMessage="label too short (More than {{ limit }} characters needed !)",
     *     maxMessage="label too long (less than {{ limit }} characters needed !)" )
     */
    private $nodeLabel;



    /**
     * @ORM\OneToMany(targetEntity=Block::class, mappedBy="node", orphanRemoval=true)
     */
    private $blocks;

    /**
     * @ORM\Column(type="string", length=5)
     * @ORM\OneToMany (targetEntity="App\Entity\NFT\Nft", mappedBy="currency")
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Length(min="3",max=5,minMessage="Code must be >= {{ limit }} Cahracters",
     *     maxMessage="Code must be <= {{ limit }} Cahracters")
     */
    private $coinCode;

    /**
     * @ORM\Column(type="float")
     * @Assert\Positive
     */
    private $nodeReward;
    


    public function __construct()
    {
        $this->blocks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNodeLabel(): ?string
    {
        return $this->nodeLabel;
    }

    public function setNodeLabel(string $NodeLabel): self
    {
        $this->nodeLabel = $NodeLabel;

        return $this;
    }

    /**
     * @return Collection|Block[]
     */
    public function getBlocks(): Collection
    {
        return $this->blocks;
    }

    public function addBlock(Block $block): self
    {
        if (!$this->blocks->contains($block)) {
            $this->blocks[] = $block;
            $block->setNode($this);
        }

        return $this;
    }

    public function removeBlock(Block $block): self
    {
        if ($this->blocks->removeElement($block)) {
            // set the owning side to null (unless already changed)
            if ($block->getNode() === $this) {
                $block->setNode(null);
            }
        }

        return $this;
    }

    public function getCoinCode(): ?string
    {
        return $this->coinCode;
    }

    public function setCoinCode(string $coinCode): self
    {
        $this->coinCode = $coinCode;

        return $this;
    }

    public function getNodeReward(): ?float
    {
        return $this->nodeReward;
    }

    public function setNodeReward(float $nodeReward): self
    {
        $this->nodeReward = $nodeReward;

        return $this;
    }
}
