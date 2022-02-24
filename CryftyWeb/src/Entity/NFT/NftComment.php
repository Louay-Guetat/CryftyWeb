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
     * @ORM\Column (type="string")
     */
    private $comment;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $postDate;

    /**
     * @ORM\Column (type="integer")
     */
    private $likes;

    /**
     * @ORM\Column (type="integer")
     */
    private $dislikes;


    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\NFT\Nft", inversedBy="comments")
     */
    private $nft;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Users\Client", inversedBy="comments")
     */
    private $user;



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

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment): void
    {
        $this->comment = $comment;
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
    public function getDislikes()
    {
        return $this->dislikes;
    }

    /**
     * @param mixed $dislikes
     */
    public function setDislikes($dislikes): void
    {
        $this->dislikes = $dislikes;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

}
