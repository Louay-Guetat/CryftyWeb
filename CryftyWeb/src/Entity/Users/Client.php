<?php

namespace App\Entity\Users;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @UniqueEntity("email")
 */
class Client extends User
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Users\SupportTicket", mappedBy="Client")
     */
    private $supportticket;


    /**
     * @Assert\Length(min=5,max=255)
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @Assert\Length(min=5,max=255)
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex("/^[0-9]{9}$/")
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *@Assert\Range(min=10,max=90)
     */
    private $age;

    /**
     * @Assert\Length(min=5,max=255)
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;


    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }
}
