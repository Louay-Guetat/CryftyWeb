<?php

namespace App\Entity\Users;

use App\Entity\Chat\GroupChat;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("username")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"admin"="Admin", "moderator"="Moderator", "client"="Client"})
 * @UniqueEntity("username")
 */
abstract class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("owner:read")
     * @Groups("post:read")
     */
    protected $id;

    /**
     * @ORM\Column(name="username",type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;


    /**
     * @param $Groups
     */
    public function __construct()
    {
        $this->Groups = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments): void
    {
        $this->comments = $comments;
    }




    /**
     * @ORM\OneToMany (targetEntity="App\Entity\NFT\NftComment", mappedBy="user")
     */
    private $comments;
    /**
     * @ORM\OneToMany (targetEntity="App\Entity\Blog\BlogComment", mappedBy="user")
     * @Groups("post:read")
     */
    private $commentsb;


    public function getId()
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername()
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    /*******************************************/


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Chat\GroupChat")

     */
    private $Groups;


    /**
     * @return mixed
     */
    public function getGroups()
    {
        return $this->Groups;
    }

    /**
     * @param mixed $Groups
     */
    public function setGroups($Groups): void
    {
        $this->Groups = $Groups;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->Group;
    }

    /**
     * @param mixed $Group
     */
    public function setGroup($Group): void
    {
        $this->Group = $Group;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->Message;
    }

    /**
     * @param mixed $Message
     */
    public function setMessage($Message): void
    {
        $this->Message = $Message;
    }

    /**
     * @return mixed
     */
    public function getPrivateChatSender()
    {
        return $this->privateChatSender;
    }

    /**
     * @param mixed $privateChatSender
     */
    public function setPrivateChatSender($privateChatSender): void
    {
        $this->privateChatSender = $privateChatSender;
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chat\PrivateChat", mappedBy="Received")
     */
    private $privateChatReceived;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chat\GroupChat", mappedBy="Owner")

     */
    private $Group;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chat\Message", mappedBy="Sender")
     */
    private $Message;




    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chat\PrivateChat", mappedBy="Sender")
     */
    private $privateChatSender;

    /**
     * @ORM\Column (type="boolean")
     */
    private $isActive;

    /**
     * @return mixed
     */
    public function getCommentsb()
    {
        return $this->commentsb;
    }

    /**
     * @param mixed $commentsb
     */
    public function setCommentsb($commentsb): void
    {
        $this->commentsb = $commentsb;
    }

    /**
     * @return mixed
     */
    public function getPrivateChatReceived()
    {
        return $this->privateChatReceived;
    }

    /**
     * @param mixed $privateChatReceived
     */
    public function setPrivateChatReceived($privateChatReceived): void
    {
        $this->privateChatReceived = $privateChatReceived;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }

    public function addGroup(GroupChat $groupChat): self
    {
        if (!$this->Groups->contains($groupChat)) {
            $this->Groups[] = $groupChat;
        }
        return $this;
    }
    public function removeGroup(GroupChat $groupChat): self
    {
        if ($this->Groups->contains( $groupChat)) {
            $this->Groups->removeElement( $groupChat);
        }
        return $this;
    }


}

