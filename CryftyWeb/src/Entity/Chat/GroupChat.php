<?php

namespace App\Entity\Chat;

use App\Entity\Users\User;
use App\Repository\GroupChatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupChatRepository::class)
 */
class GroupChat extends Conversation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     * @Groups("owner:read")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Users\User")
     * @Groups("post:read")
     */
    private $participants;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users\User", inversedBy="Group")
     * @Groups("owner:read")
     */
    private $Owner;

    /**
     * @param $participants
     */
    public function __construct()
    {
        $this->participants = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->Owner;
    }

    /**
     * @param mixed $Owner
     */
    public function setOwner($Owner): void
    {
        $this->Owner = $Owner;
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







    /**
     * @return mixed
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param mixed $participants
     */
    public function setParticipants($participants): void
    {
        $this->participants = $participants;
    }
    public function getId(): ?int
    {
        return $this->id;
    }


    public function addUser(User $user): self
    {
        if (!$this->participants ->contains($user)) {
            $this->participants [] = $user;
            $user->addGroup($this);
        }
        return $this;
    }
    public function removeUser(User $user): self
    {
        for($i=0;$i<count($this->participants);$i++)
        {
            if($user->getId()==$this->participants[$i]->getId())
            {
                unset($this->participants[$i]);
            }
        }return $this;
    }
}
