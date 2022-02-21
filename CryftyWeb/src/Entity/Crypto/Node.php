<?php

namespace App\Entity\Crypto;

use App\Repository\NodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
    private $NodeLabel;

    /**
     * @ORM\OneToMany(targetEntity=Block::class, mappedBy="node", orphanRemoval=true)
     */
    private $Blocks;

    /**
     * @ORM\OneToMany  (targetEntity="App\Entity\NFT\Nft", mappedBy="currency")
     */
    private $nfts;

    public function __construct()
    {
        $this->Blocks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNodeLabel(): ?string
    {
        return $this->NodeLabel;
    }

    public function setNodeLabel(string $NodeLabel): self
    {
        $this->NodeLabel = $NodeLabel;

        return $this;
    }

    /**
     * @return Collection|Block[]
     */
    public function getBlocks(): Collection
    {
        return $this->Blocks;
    }

    public function addBlock(Block $block): self
    {
        if (!$this->Blocks->contains($block)) {
            $this->Blocks[] = $block;
            $block->setNode($this);
        }

        return $this;
    }

    public function removeBlock(Block $block): self
    {
        if ($this->Blocks->removeElement($block)) {
            // set the owning side to null (unless already changed)
            if ($block->getNode() === $this) {
                $block->setNode(null);
            }
        }

        return $this;
    }
}
