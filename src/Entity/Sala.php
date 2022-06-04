<?php

namespace App\Entity;

use App\Repository\SalaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SalaRepository::class)
 */
class Sala
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="salas")
     */
    private $idpersona1;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="salas")
     */
    private $idpersona2;

    /**
     * @ORM\OneToMany(targetEntity=Mensaje::class, mappedBy="idsala")
     */
    private $mensajes;

    public function __construct()
    {
        $this->mensajes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdpersona1(): ?User
    {
        return $this->idpersona1;
    }

    public function setIdpersona1(?User $idpersona1): self
    {
        $this->idpersona1 = $idpersona1;

        return $this;
    }

    public function getIdpersona2(): ?User
    {
        return $this->idpersona2;
    }

    public function setIdpersona2(?User $idpersona2): self
    {
        $this->idpersona2 = $idpersona2;

        return $this;
    }

    /**
     * @return Collection<int, Mensaje>
     */
    public function getMensajes(): Collection
    {
        return $this->mensajes;
    }

    public function addMensaje(Mensaje $mensaje): self
    {
        if (!$this->mensajes->contains($mensaje)) {
            $this->mensajes[] = $mensaje;
            $mensaje->setIdsala($this);
        }

        return $this;
    }

    public function removeMensaje(Mensaje $mensaje): self
    {
        if ($this->mensajes->removeElement($mensaje)) {
            // set the owning side to null (unless already changed)
            if ($mensaje->getIdsala() === $this) {
                $mensaje->setIdsala(null);
            }
        }

        return $this;
    }
}
