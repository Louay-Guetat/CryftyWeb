<?php

namespace App\Entity\Chat;

use App\Repository\PrivateChatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrivateChatRepository::class)
 */
class PrivateChat extends Conversation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users\User", inversedBy="privateChatSender")
     */
    private $Sender;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users\User", inversedBy="privateChatReceived")
     */
    private $Received;

    /**
     * @return mixed
     */
    public function getSenderP()
    {
        return $this->SenderP;
    }

    /**
     * @param mixed $SenderP
     */
    public function setSenderP($SenderP): void
    {
        $this->SenderP = $SenderP;
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
