<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsuarioRepository::class)
 */
class Usuario
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $password;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechapagos;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechareplica;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFechapagos(): ?\DateTimeInterface
    {
        return $this->fechapagos;
    }

    public function setFechapagos(?\DateTimeInterface $fechapagos): self
    {
        $this->fechapagos = $fechapagos;

        return $this;
    }

    public function getFechareplica(): ?\DateTimeInterface
    {
        return $this->fechareplica;
    }

    public function setFechareplica(?\DateTimeInterface $fechareplica): self
    {
        $this->fechareplica = $fechareplica;

        return $this;
    }
}
