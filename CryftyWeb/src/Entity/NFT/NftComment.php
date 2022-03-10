<?php

namespace App\Entity\NFT;

use App\Repository\NftCommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Vangrg\ProfanityBundle\Validator\Constraints as ProfanityAssert;

/**
 * @ORM\Entity(repositoryClass=NftCommentRepository::class)
 */
class NftComment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ("comments:read")
     * @Groups ("user:read")
     */
    private $id;

    /**
     * @ORM\Column (type="string")
     * @ProfanityAssert\ProfanityCheck
     * @Groups ("comments:read")
     * @Groups ("user:read")
     */
    private $comment;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     * @Groups ("comments:read")
     * @Groups ("user:read")
     */
    private $postDate;

    /**
     * @ORM\Column (type="integer")
     * @Groups ("comments:read")
     * @Groups ("user:read")
     */
    private $likes;

    /**
     * @ORM\Column (type="integer")
     * @Groups ("comments:read")
     * @Groups ("user:read")
     */
    private $dislikes;


    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\NFT\Nft", inversedBy="comments")
     */
    private $nft;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Users\Client", inversedBy="comments")
     * @Groups ("user:read")
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
