<?php

namespace App\Entity\Users;

use App\Repository\ModeratorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModeratorRepository::class)
 */
class Moderator extends User
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $FirstName;


    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(string $FirstName): self
    {
        $this->FirstName = $FirstName;

        return $this;
    }
}
