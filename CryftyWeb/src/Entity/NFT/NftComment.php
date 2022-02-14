<?php

namespace App\Entity\NFT;

use App\Repository\NftCommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=NftCommentRepository::class)
 */
class NftComment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $postDate;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\NFT\Nft", inversedBy="comments")
     */
    private $nft;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Users\Client", inversedBy="comments")
     */
    private $client;



    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPostDate()
    {
        return $this->postDate;
    }

    /**
     * @param mixed $postDate
     */
    public function setPostDate($postDate): void
    {
        $this->postDate = $postDate;
    }

    /**
     * @return mixed
     */
    public function getNft()
    {
        return $this->nft;
    }

    /**
     * @param mixed $nft
     */
    public function setNft($nft): void
    {
        $this->nft = $nft;
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     */
    public function setClient($client): void
    {
        $this->client = $client;
    }

}
