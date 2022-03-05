<?php

namespace App\Entity\Blog;

use App\Repository\BlogCommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BlogCommentRepository::class)
 */
class BlogComment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @ORM\Column (type="string")
     * @Groups("post:read")
     */
    private $comment;
    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     * @Groups("post:read")
     */
    private $postDate;
    /**
     * @ORM\Column (type="integer")
     * @Groups("post:read")
     */
    private $likes;

    /**
     * @ORM\Column (type="integer")
     * @Groups("post:read")
     */
    private $dislikes;
    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Blog\BlogArticle", inversedBy="comments")
     * @Groups("post:read")
     */
    private $article;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Users\Client", inversedBy="commentsb")
     * @Groups("post:read")
     */
    private $user;

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
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param mixed $article
     */
    public function setArticle($article): void
    {
        $this->article = $article;
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

