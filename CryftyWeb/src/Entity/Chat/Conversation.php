<?php

namespace App\Entity\Chat;

use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Collection;

use Symfony\Component\Validator\Constraints as Assert;
/**
 *@ORM\Entity(repositoryClass=ConversationRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"groupchat"="GroupChat", "privatechat"="PrivateChat"})
 */
class Conversation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     */

    protected $nom;

    /**
     * @param $messages
     */
    /*public function __construct($messages)
    {
        $this->messages = ArrayCollection();
    }*/

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chat\Message", mappedBy="conversation")
     */
    private  $messages;
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
