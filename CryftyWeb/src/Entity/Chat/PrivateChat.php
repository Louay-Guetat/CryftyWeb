<?php

namespace App\Entity\Chat;

use App\Repository\PrivateChatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PrivateChatRepository::class)
 */
class PrivateChat extends Conversation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("messages:read")
     * @Groups("PrivateChat:read")
     */
    protected $id;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users\User", inversedBy="privateChatSender")
     * @Groups("PrivateChat:read")
     */
    private $Sender;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users\User", inversedBy="privateChatReceived")
     * @Groups("PrivateChat:read")
     */
    private $Received;

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
    public function setSenderP($Sender): void
    {
        $this->Sender = $Sender;
    }


    /**
     * @return mixed
     */
    public function getReceived()
    {
        return $this->Received;
    }

    /**
     * @param mixed $Received
     */
    public function setReceived($Received): void
    {
        $this->Received = $Received;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
