<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $objet = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    

     #[ORM\OneToOne(cascade: ['persist', 'remove'])]
     #[ORM\JoinColumn(nullable: false)]
     private ?Reclamation $relation_reclamation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;

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

   

    public function getRelationReclamation(): ?Reclamation
    {
        return $this->relation_reclamation;
    }

    public function setRelationReclamation(Reclamation $relation_reclamation): self
    {
        $this->relation_reclamation = $relation_reclamation;

        return $this;
    }
}
