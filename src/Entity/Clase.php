<?php

namespace App\Entity;

use App\Repository\ClaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClaseRepository::class)
 */
class Clase
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $descripclase;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $titulo;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="clases")
     */
    private $idprofe;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $claveprivada;

    /**
     * @ORM\OneToMany(targetEntity=Contenido::class, mappedBy="idclase")
     */
    private $contenidos;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="pertenece")
     */
    private $users;

    public function __construct()
    {
        $this->contenidos = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripclase(): ?string
    {
        return $this->descripclase;
    }

    public function setDescripclase(string $descripclase): self
    {
        $this->descripclase = $descripclase;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getIdprofe(): ?User
    {
        return $this->idprofe;
    }

    public function setIdprofe(?User $idprofe): self
    {
        $this->idprofe = $idprofe;

        return $this;
    }

    public function getClaveprivada(): ?string
    {
        return $this->claveprivada;
    }

    public function setClaveprivada(?string $claveprivada): self
    {
        $this->claveprivada = $claveprivada;

        return $this;
    }

    /**
     * @return Collection<int, Contenido>
     */
    public function getContenidos(): Collection
    {
        return $this->contenidos;
    }

    public function addContenido(Contenido $contenido): self
    {
        if (!$this->contenidos->contains($contenido)) {
            $this->contenidos[] = $contenido;
            $contenido->setIdclase($this);
        }

        return $this;
    }

    public function removeContenido(Contenido $contenido): self
    {
        if ($this->contenidos->removeElement($contenido)) {
            // set the owning side to null (unless already changed)
            if ($contenido->getIdclase() === $this) {
                $contenido->setIdclase(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addPertenece($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removePertenece($this);
        }

        return $this;
    }
}
