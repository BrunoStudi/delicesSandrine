<?php

namespace App\Entity;

use App\Repository\DietRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DietRepository::class)]
#[ORM\Table(name: '`diet`')]
class Diet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $dietName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDietName(): ?string
    {
        return $this->dietName;
    }

    public function setDietName(string $dietName): static
    {
        $this->dietName = $dietName;

        return $this;
    }

    /* conversion en string de DietName.(necessaire pour la recuperation de DietName 
    lors de la creation d'un utilisateur en mode admin sinon erreur de recuperation 
    entity nous on veut un "string".)*/
    public function __toString(): string
    {
        return $this->getDietName();
    }
}
