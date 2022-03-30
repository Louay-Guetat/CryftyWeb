<?php

namespace App\Entity\Users;

use App\Repository\SupportTicketRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;


/**
 * @ORM\Entity(repositoryClass=SupportTicketRepository::class)
 *
 */
class SupportTicket
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users\Client", inversedBy="supportticket")
     */
    private $Client;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("supportTicket:read")
     */
    private $id;

    /**
     * @Assert\Length(min=3,max=255)
     * @Groups("supportTicket:read")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("supportTicket:read")
     * @Assert\NotBlank(message="Email is required")
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.")
     */
    private $email;

    /**
     * @Assert\NotBlank
     * @Groups("supportTicket:read")
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @Assert\NotBlank
     * @Groups("supportTicket:read")
     * @ORM\Column(type="string", length=255)
     */
    private $message;

    /**
     * @ORM\Column (type="string")
     * @Groups("supportTicket:read")
     */
    private $etat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->Client;
    }

    /**
     * @param mixed $Client
     */
    public function setClient($Client): void
    {
        $this->Client = $Client;
    }

    /**
     * @return mixed
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param mixed $etat
     */
    public function setEtat($etat): void
    {
        $this->etat = $etat;
    }

}
