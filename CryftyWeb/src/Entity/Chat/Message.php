<?php

namespace App\Entity\Chat;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")

     */
    private $id;
    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string")

     */
    private $contenu;
    /**
     * @Assert\DateTime
     * @ORM\Column(type="datetime")
     * @var string A "Y-m-d H:i:s" formatted value

     */

    private $createdAt;



    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users\User", inversedBy="Message")
     */
    private $Sender;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chat\Conversation", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)


     */
    private $conversation;

    /**
     * @param string $createdAt
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();

    }

    /**
     * @return mixed
     */
    public function getConversation()
    {
        return $this->conversation;
    }

    /**
     * @param mixed $conversation
     */
    public function setConversation($conversation): void
    {
        $this->conversation = $conversation;
    }

    /**
     * @return mixed
     */
    public function getSender()
    {
        return $this->Sender;
    }

    /**
     * @param mixed $Sender
     */
    public function setSender($Sender): void
    {
        $this->Sender = $Sender;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * @param mixed $contenu
     */
    public function setContenu($contenu): void
    {
        $this->contenu = $contenu;
    }

/**@return \DateTime|null
 * @var \DateTime|null
 */
    public function getCreatedAt(): ?\DateTime  {  return $this->createdAt; }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }


}
