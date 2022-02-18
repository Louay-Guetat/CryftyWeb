<?php

namespace App\Entity\Blog;

use App\Repository\BlogCommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BlogCommentRepository::class)
 */
class BlogComment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;
    public function getUname(): ?string
    {
        return $this->username;
    }


    public function setUname(string $username): self
    {
        $this->username = $username;

        return $this;
    }
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comment;
    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $dateC;
    public function getDateC(): ?string
    {
        return $this->dateC;
    }

    public function setDateC(string $dateC): self
    {
        $this->dateC = $dateC;

        return $this;
    }


}
