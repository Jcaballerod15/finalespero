<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=Clase::class, mappedBy="idprofe")
     */
    private $clases;

    /**
     * @ORM\ManyToMany(targetEntity=Clase::class, inversedBy="users")
     */
    private $pertenece;

    /**
     * @ORM\OneToMany(targetEntity=Sala::class, mappedBy="idpersona1")
     */
    private $salas;

    /**
     * @ORM\OneToMany(targetEntity=Mensaje::class, mappedBy="idemisor")
     */
    private $mensajes;

    public function __construct()
    {
        $this->clases = new ArrayCollection();
        $this->pertenece = new ArrayCollection();
        $this->salas = new ArrayCollection();
        $this->mensajes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection<int, Clase>
     */
    public function getClases(): Collection
    {
        return $this->clases;
    }

    public function addClase(Clase $clase): self
    {
        if (!$this->clases->contains($clase)) {
            $this->clases[] = $clase;
            $clase->setIdprofe($this);
        }

        return $this;
    }

    public function removeClase(Clase $clase): self
    {
        if ($this->clases->removeElement($clase)) {
            // set the owning side to null (unless already changed)
            if ($clase->getIdprofe() === $this) {
                $clase->setIdprofe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Clase>
     */
    public function getPertenece(): Collection
    {
        return $this->pertenece;
    }

    public function addPertenece(Clase $pertenece): self
    {
        if (!$this->pertenece->contains($pertenece)) {
            $this->pertenece[] = $pertenece;
        }

        return $this;
    }

    public function removePertenece(Clase $pertenece): self
    {
        $this->pertenece->removeElement($pertenece);

        return $this;
    }

    /**
     * @return Collection<int, Sala>
     */
    public function getSalas(): Collection
    {
        return $this->salas;
    }

    public function addSala(Sala $sala): self
    {
        if (!$this->salas->contains($sala)) {
            $this->salas[] = $sala;
            $sala->setIdpersona1($this);
        }

        return $this;
    }

    public function removeSala(Sala $sala): self
    {
        if ($this->salas->removeElement($sala)) {
            // set the owning side to null (unless already changed)
            if ($sala->getIdpersona1() === $this) {
                $sala->setIdpersona1(null);
            }
        }

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
            $mensaje->setIdemisor($this);
        }

        return $this;
    }

    public function removeMensaje(Mensaje $mensaje): self
    {
        if ($this->mensajes->removeElement($mensaje)) {
            // set the owning side to null (unless already changed)
            if ($mensaje->getIdemisor() === $this) {
                $mensaje->setIdemisor(null);
            }
        }

        return $this;
    }
}
