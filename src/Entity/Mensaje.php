<?php

namespace App\Entity;

use App\Repository\MensajeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MensajeRepository::class)
 */
class Mensaje
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Sala::class, inversedBy="mensajes")
     */
    private $idsala;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="mensajes")
     */
    private $idemisor;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $texto;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaenvio;

    /**
     * @ORM\Column(type="integer")
     */
    private $leido;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdsala(): ?Sala
    {
        return $this->idsala;
    }

    public function setIdsala(?Sala $idsala): self
    {
        $this->idsala = $idsala;

        return $this;
    }

    public function getIdemisor(): ?User
    {
        return $this->idemisor;
    }

    public function setIdemisor(?User $idemisor): self
    {
        $this->idemisor = $idemisor;

        return $this;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(string $texto): self
    {
        $this->texto = $texto;

        return $this;
    }

    public function getFechaenvio(): ?\DateTimeInterface
    {
        return $this->fechaenvio;
    }

    public function setFechaenvio(\DateTimeInterface $fechaenvio): self
    {
        $this->fechaenvio = $fechaenvio;

        return $this;
    }

    public function getLeido(): ?int
    {
        return $this->leido;
    }

    public function setLeido(int $leido): self
    {
        $this->leido = $leido;

        return $this;
    }
}
