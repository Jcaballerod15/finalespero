<?php

namespace App\Entity;

use App\Repository\ContenidoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContenidoRepository::class)
 */
class Contenido
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Clase::class, inversedBy="contenidos")
     */
    private $idclase;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechapublica;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $texto;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $imagen;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $video;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $urlimagen;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdclase(): ?Clase
    {
        return $this->idclase;
    }

    public function setIdclase(?Clase $idclase): self
    {
        $this->idclase = $idclase;

        return $this;
    }

    public function getFechapublica(): ?\DateTimeInterface
    {
        return $this->fechapublica;
    }

    public function setFechapublica(\DateTimeInterface $fechapublica): self
    {
        $this->fechapublica = $fechapublica;

        return $this;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(?string $texto): self
    {
        $this->texto = $texto;

        return $this;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function setImagen($imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getUrlimagen(): ?string
    {
        return $this->urlimagen;
    }

    public function setUrlimagen(?string $urlimagen): self
    {
        $this->urlimagen = $urlimagen;

        return $this;
    }
}
